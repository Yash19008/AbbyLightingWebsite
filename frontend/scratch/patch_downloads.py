import sys
import re

def patch():
    path = "components/downloads/DownloadsPageContent.tsx"
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    # Replace closeModal body
    old_close_modal = """  const closeModal = () => {
    setModalOpen(false);
    setSelectedCardId(null);
    setSubmitError(null);
    setSubmitting(false);
  };"""
    new_close_modal = """  const closeModal = () => {
    setModalOpen(false);
  };"""
    content = content.replace(old_close_modal, new_close_modal)

    # We can just regex remove the whole chunk of unused functions
    # handleFormChange, handleSelectChange, triggerFileDownload, handleFormSubmit
    # Because SharedInquiryModal does the submission and triggering the download.
    # Wait, let's look for these functions and remove them.
    # It's safer to just remove the lines using regex or string replace.
    
    start_pattern = "  const handleFormChange = (e: React.ChangeEvent"
    # Find the end of handleFormSubmit
    # Let's just find exactly what to remove using regex
    
    match = re.search(r"(  const handleFormChange = \(e: React\.ChangeEvent.*?)  const filteredCatalogues =", content, flags=re.DOTALL)
    if match:
        content = content.replace(match.group(1), "")
        
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)

patch()
