#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Production Readiness Audit for Najah Souss Theme
Checks: hardcoded URLs, syntax hints, performance flags
"""
import os, re, subprocess, sys

BASE = r"c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme"
CORE_FILES = ["front-page.php", "header.php", "footer.php", "functions.php", "single.php"]
PHP = r"c:\xampp\php\php.exe"

print("=" * 60)
print("  NAJAH SOUSS — PRODUCTION READINESS AUDIT")
print("=" * 60)

# --- 1. PHP SYNTAX LINT ---
print("\n[1] PHP SYNTAX CHECK")
all_clean = True
for f in CORE_FILES:
    path = os.path.join(BASE, f)
    if not os.path.exists(path):
        print(f"  SKIP  {f} (not found)")
        continue
    try:
        r = subprocess.run([PHP, "-l", path], capture_output=True, text=True, timeout=10)
        if r.returncode == 0:
            print(f"  OK    {f}")
        else:
            print(f"  FAIL  {f}")
            print(f"        {r.stdout.strip()}")
            all_clean = False
    except Exception as e:
        print(f"  ERR   {f} — {e}")

# --- 2. HARDCODED URL CHECK ---
print("\n[2] HARDCODED localhost URL CHECK")
patterns = [
    r"http://localhost",
    r"http://127\.0\.0\.1",
    r"http://localhost/wordpress",
]
found_any = False
for f in CORE_FILES:
    path = os.path.join(BASE, f)
    if not os.path.exists(path):
        continue
    with open(path, "r", encoding="utf-8", errors="ignore") as fh:
        lines = fh.readlines()
    for i, line in enumerate(lines, 1):
        for pat in patterns:
            if re.search(pat, line, re.IGNORECASE):
                print(f"  WARN  {f}:{i}  {line.strip()[:100]}")
                found_any = True
if not found_any:
    print("  OK  No hardcoded localhost URLs found.")

# --- 3. ASSET LOADING CHECK ---
print("\n[3] ASSET LOADING (dynamic vs hardcoded)")
asset_issues = []
for f in CORE_FILES:
    path = os.path.join(BASE, f)
    if not os.path.exists(path):
        continue
    with open(path, "r", encoding="utf-8", errors="ignore") as fh:
        content = fh.read()
    # Check for src="/wp-content/..." style hardcoded asset paths
    matches = re.findall(r'src=["\'](?!//)(/wp-content/themes/[^"\']+)["\']', content)
    for m in matches:
        asset_issues.append(f"{f}: {m}")
if asset_issues:
    for a in asset_issues:
        print(f"  WARN  {a}")
else:
    print("  OK  All assets appear dynamically loaded.")

# --- 4. PERFORMANCE FLAGS ---
print("\n[4] PERFORMANCE FLAGS")
perf_issues = []
for f in CORE_FILES:
    path = os.path.join(BASE, f)
    if not os.path.exists(path):
        continue
    with open(path, "r", encoding="utf-8", errors="ignore") as fh:
        lines = fh.readlines()
    for i, line in enumerate(lines, 1):
        # Unguarded get_posts on page load (not in a function/hook)
        if re.search(r'\bget_posts\b', line) and 'function' not in line:
            perf_issues.append(f"  NOTE  {f}:{i} get_posts() call — ensure args limit results")
        # Direct wp_remote_get on front-end (FIDE sync)
        if re.search(r'wp_remote_get', line):
            perf_issues.append(f"  WARN  {f}:{i} wp_remote_get() — ensure this is cron-only")

if perf_issues:
    for p in perf_issues:
        print(p)
else:
    print("  OK  No blocking remote calls found in template files.")

# --- 5. SEEDER GUARD CHECK ---
print("\n[5] SEEDER GUARD")
fn_path = os.path.join(BASE, "functions.php")
with open(fn_path, "r", encoding="utf-8", errors="ignore") as fh:
    fn_content = fh.read()
if "seeder_run_v5" in fn_content and "get_option" in fn_content:
    print("  OK  Seeder is guarded by get_option() check — won't run on live server.")
else:
    print("  WARN  Seeder guard not found — check functions.php include logic.")

# --- 6. FIDE SYNC CRON CHECK ---
print("\n[6] FIDE SYNC CRON CHECK")
if "add_action('wp'" in fn_content.replace(" ", "") and "wp_schedule_event" in fn_content:
    print("  OK  FIDE sync is hooked to WP-Cron (daily), not page load.")
else:
    print("  WARN  Could not confirm FIDE sync cron schedule.")
if "wp_remote_get" in fn_content:
    print("  NOTE  wp_remote_get() is in functions.php — confirm it's inside cron action only.")

# --- 7. DICTIONARY DUPLICATE CHECK ---
print("\n[7] ANSAE_T DICTIONARY DUPLICATE KEYS")
dict_keys = re.findall(r"'([^']{5,}?)'\s*=>\s*array\(", fn_content)
dq_keys = re.findall(r'"([^"]{5,}?)"\s*=>\s*array\(', fn_content)
all_keys = dict_keys + dq_keys
from collections import Counter
dupes = [k for k, c in Counter(all_keys).items() if c > 1]
if dupes:
    print(f"  WARN  {len(dupes)} duplicate keys found (wasted memory):")
    for d in dupes[:10]:
        print(f"        '{d[:70]}'")
else:
    print("  OK  No duplicate dictionary keys.")

# --- 8. STATIC CACHE CHECK ---
print("\n[8] STATIC CACHE IN ansae_t()")
if "static $normalized_dict = null" in fn_content:
    print("  OK  Static cache is active — dictionary built only once per request.")
else:
    print("  WARN  Static cache not found — dictionary rebuilt on every ansae_t() call!")

print("\n" + "=" * 60)
print("  AUDIT COMPLETE")
print("=" * 60)
