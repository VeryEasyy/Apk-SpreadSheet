/**
 * Report List Page JavaScript
 * Handles dropdown positioning, delete confirmation, and table scroll
 */

document.addEventListener("DOMContentLoaded", function () {
    initializeDropdowns();
    initializeTableScroll();
    loadSweetAlertIfNeeded();
});

/**
 * Initialize dropdown menus with proper positioning
 */
function initializeDropdowns() {
    const dropdowns = document.querySelectorAll(".dropdown");

    dropdowns.forEach((dropdown) => {
        const toggle = dropdown.querySelector(".dropdown-toggle-modern");
        const menu = dropdown.querySelector(".dropdown-menu-modern");

        if (!toggle || !menu) return;

        // Show dropdown on click
        toggle.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Close all other dropdowns
            closeAllDropdowns();

            // Toggle current dropdown
            const isVisible = menu.classList.contains("show");

            if (!isVisible) {
                // Position and show
                positionDropdown(toggle, menu);
                menu.classList.add("show");

                // Add scroll event listener for shadows
                updateScrollShadows(menu);
                menu.addEventListener("scroll", () =>
                    updateScrollShadows(menu)
                );
            } else {
                menu.classList.remove("show");
            }
        });

        // Close dropdown when user clicks a menu item inside it
        menu.addEventListener("click", function (e) {
            const item = e.target.closest(
                ".dropdown-item, [data-close-modal], a, button"
            );
            if (!item) return;

            // Close the dropdown menu
            menu.classList.remove("show");

            // If the dropdown is inside a modal, close the modal as well
            const modalEl =
                dropdown.closest(".modal") || item.closest(".modal");
            if (modalEl) {
                closeModal(modalEl);
            }
        });

        // Reposition on scroll
        window.addEventListener(
            "scroll",
            () => {
                if (menu.classList.contains("show")) {
                    positionDropdown(toggle, menu);
                }
            },
            true
        );

        // Reposition on window resize
        window.addEventListener("resize", () => {
            if (menu.classList.contains("show")) {
                positionDropdown(toggle, menu);
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener("click", function (e) {
        if (!e.target.closest(".dropdown")) {
            closeAllDropdowns();
        }
    });
}

/**
 * Attempt to close a modal element using available APIs
 */
function closeModal(modalEl) {
    if (!modalEl) return;

    // 1) Bootstrap 5 native API
    try {
        if (typeof bootstrap !== "undefined" && bootstrap.Modal) {
            const inst =
                bootstrap.Modal.getInstance(modalEl) ||
                new bootstrap.Modal(modalEl);
            inst.hide();
            return;
        }
    } catch (err) {
        // ignore and try other methods
    }

    // 2) jQuery bootstrap modal
    try {
        if (typeof jQuery !== "undefined" && jQuery(modalEl).modal) {
            jQuery(modalEl).modal("hide");
            return;
        }
    } catch (err) {
        // ignore
    }

    // 3) Vanilla fallback
    modalEl.classList.remove("show");
    modalEl.setAttribute("aria-hidden", "true");
    modalEl.removeAttribute("aria-modal");
    modalEl.style.display = "none";

    document.body.classList.remove("modal-open");

    const backdrops = document.querySelectorAll(".modal-backdrop");
    backdrops.forEach((b) => b.parentNode && b.parentNode.removeChild(b));
}

/**
 * Update scroll shadow indicators
 */
function updateScrollShadows(menu) {
    const scrollTop = menu.scrollTop;
    const scrollHeight = menu.scrollHeight;
    const clientHeight = menu.clientHeight;
    const scrollBottom = scrollHeight - scrollTop - clientHeight;

    if (scrollTop > 5) {
        menu.classList.add("has-scroll-top");
    } else {
        menu.classList.remove("has-scroll-top");
    }

    if (scrollBottom > 5) {
        menu.classList.add("has-scroll-bottom");
    } else {
        menu.classList.remove("has-scroll-bottom");
    }
}

/**
 * Position dropdown menu using fixed positioning
 */
function positionDropdown(toggle, menu) {
    const toggleRect = toggle.getBoundingClientRect();
    const menuWidth = 220;
    const viewportHeight = window.innerHeight;
    const viewportWidth = window.innerWidth;
    const padding = 8;

    // Force menu to render to get real height
    menu.style.visibility = "hidden";
    menu.style.display = "block";
    const menuHeight = menu.scrollHeight;
    menu.style.visibility = "";
    menu.style.display = "";

    let top, left;

    const spaceBelow = viewportHeight - toggleRect.bottom - padding;
    const spaceAbove = toggleRect.top - padding;
    const maxAllowedHeight = viewportHeight - 100;

    let needsScroll = false;
    let finalHeight = menuHeight;

    if (menuHeight > maxAllowedHeight) {
        needsScroll = true;
        finalHeight = maxAllowedHeight;
    }

    // Calculate vertical position
    if (spaceBelow >= finalHeight || spaceBelow > spaceAbove) {
        top = toggleRect.bottom + padding;
        if (top + finalHeight > viewportHeight - padding) {
            top = viewportHeight - finalHeight - padding;
        }
    } else {
        top = toggleRect.top - finalHeight - padding;
        if (top < padding) {
            top = padding;
        }
    }

    // Calculate horizontal position
    left = toggleRect.right - menuWidth;

    if (left < padding) {
        left = padding;
    } else if (left + menuWidth > viewportWidth - padding) {
        left = viewportWidth - menuWidth - padding;
    }

    // Apply position
    menu.style.top = `${top}px`;
    menu.style.left = `${left}px`;

    if (needsScroll) {
        menu.style.maxHeight = `${finalHeight}px`;
        menu.style.overflowY = "auto";
    } else {
        menu.style.maxHeight = "none";
        menu.style.overflowY = "visible";
    }
}

/**
 * Close all dropdown menus
 */
function closeAllDropdowns() {
    const openMenus = document.querySelectorAll(".dropdown-menu-modern.show");
    openMenus.forEach((menu) => {
        menu.classList.remove("show");
    });
}

/**
 * Load SweetAlert2 if not already loaded (for delete confirmation)
 */
function loadSweetAlertIfNeeded() {
    if (typeof Swal === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.head.appendChild(script);
    }
}

/**
 * Delete report confirmation
 */
function hapusLaporan(id) {
    // Ensure SweetAlert2 is loaded
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 not loaded');
        return;
    }

    Swal.fire({
        title: "Delete this report?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            popup: 'simple-confirm',
            confirmButton: 'swal2-confirm',
            cancelButton: 'swal2-cancel'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById("delete-form-" + id);
            if (form) {
                form.submit();
            }
        }
    });
}

/**
 * Initialize table scroll behavior
 */
function initializeTableScroll() {
    const tableWrapper = document.querySelector(".modern-table-wrapper");

    if (!tableWrapper) return;

    const isMobile = window.innerWidth <= 768;

    if (isMobile) {
        showMobileScrollHint(tableWrapper);
    }

    tableWrapper.addEventListener(
        "scroll",
        function () {
            this.classList.remove("show-hint");
        },
        { once: true }
    );
}

/**
 * Show scroll hint for mobile users
 */
function showMobileScrollHint(wrapper) {
    if (sessionStorage.getItem("mobileScrollHintShown")) return;
    if (wrapper.scrollWidth <= wrapper.clientWidth) return;

    setTimeout(() => {
        wrapper.classList.add("show-hint");

        setTimeout(() => {
            wrapper.classList.remove("show-hint");
            sessionStorage.setItem("mobileScrollHintShown", "true");
        }, 3000);
    }, 500);
}

// Export functions
window.hapusLaporan = hapusLaporan;