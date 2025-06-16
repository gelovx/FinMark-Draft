function showTab(tabId, event) {
    const contents = document.querySelectorAll('.tab-content');
    const tabs = document.querySelectorAll('.tab');

    contents.forEach(el => el.classList.remove('active'));
    tabs.forEach(el => el.classList.remove('active'));

    const tabContent = document.getElementById(tabId);
    if (tabContent) tabContent.classList.add('active');
    if (event && event.currentTarget) event.currentTarget.classList.add('active');
}

function toggleCollapse() {
    const content = document.getElementById("aboutMore");
    if (content) {
        content.classList.toggle('active');
        content.style.display = content.classList.contains('active') ? "block" : "none";
    }
}
