from playwright.sync_api import Page, expect, sync_playwright

def test_homepage(page: Page):
    page.goto("http://localhost:3000")

    # Expect the title "Professional PowerPoint Templates"
    expect(page.get_by_role("heading", name="Professional PowerPoint Templates")).to_be_visible()

    # Expect the Navbar brand
    # Use a more specific locator to avoid ambiguity
    expect(page.get_by_role("navigation").get_by_text("otojadi")).to_be_visible()

    # Screenshot
    page.screenshot(path="/home/jules/verification/homepage.png")

if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        try:
            test_homepage(page)
        finally:
            browser.close()
