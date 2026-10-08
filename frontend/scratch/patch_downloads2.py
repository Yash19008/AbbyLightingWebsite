import sys
import re

def patch():
    path = "components/downloads/DownloadsPageContent.tsx"
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    match = re.search(r"(  const handleFormChange =.*?^  };)\n\n\n\n  return \(", content, flags=re.DOTALL | re.MULTILINE)
    if match:
        content = content.replace(match.group(1), "")
        
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)

patch()
