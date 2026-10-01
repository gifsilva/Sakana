(function () {
    var toast = document.querySelector(".alert-toast");
    if (!toast) {
        return;
    }

    var closeBtn = toast.querySelector(".alert-close");
    var timeoutId = null;

    function dismiss() {
        if (!toast || toast.classList.contains("alert-hide")) {
            return;
        }

        toast.classList.add("alert-hide");

        setTimeout(function () {
            if (toast && toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 220);
    }

    function startAutoClose() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(dismiss, 4500);
    }

    if (closeBtn) {
        closeBtn.addEventListener("click", dismiss);
    }

    toast.addEventListener("mouseenter", function () {
        clearTimeout(timeoutId);
    });

    toast.addEventListener("mouseleave", function () {
        startAutoClose();
    });

    startAutoClose();
})();