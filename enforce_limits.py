import re
import os

def enforce_limits():
    filepath = r'c:\xampp\htdocs\wordpress\wp-content\themes\najah-souss-theme\front-page.php'
    
    if not os.path.exists(filepath):
        print(f"Error: Could not find {filepath}")
        return

    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Define the exact target limits
    limits = {
        'champion': 4,
        'gallery': 3,
        'event': 3,
        'presse': 3,
        'post': 3,
        'staff': 4
    }

    modified_content = content
    for post_type, limit in limits.items():
        # Regex to match the WP_Query array for the specific post_type and modify posts_per_page
        pattern = re.compile(
            rf"('post_type'\s*=>\s*'{post_type}',\s*'posts_per_page'\s*=>\s*)\d+",
            re.IGNORECASE
        )
        # Substitute the matched count with the target limit
        modified_content = pattern.sub(rf"\g<1>{limit}", modified_content)

    if content != modified_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(modified_content)
        print("Success: Updated WP_Query limits in front-page.php.")
    else:
        print("Notice: No changes were needed. All limits are already correct.")

if __name__ == '__main__':
    enforce_limits()
