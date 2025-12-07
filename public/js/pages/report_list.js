/**
 * Report List Page JavaScript
 * Handles dropdown positioning, delete confirmation, and notifications
 */

document.addEventListener("DOMContentLoaded", function () {
    initializeDropdowns();
    initializeNotifications();
    initializeTableScroll();
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

        // Close dropdown when user clicks a menu item inside it.
        // Matches common clickable elements; you can adjust selectors as needed.
        menu.addEventListener("click", function (e) {
            const item = e.target.closest(
                ".dropdown-item, [data-close-modal], a, button"
            );
            if (!item) return; // not a selectable element

            // Close the dropdown menu
            menu.classList.remove("show");

            // If the dropdown is inside a modal (or the clicked item is inside a modal),
            // close the modal as well.
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
 * Attempt to close a modal element using available APIs.
 * Supports Bootstrap 5 (native), jQuery .modal('hide'), and a vanilla fallback.
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

    // 3) Vanilla fallback: remove show class, aria attributes, and backdrop
    modalEl.classList.remove("show");
    modalEl.setAttribute("aria-hidden", "true");
    modalEl.removeAttribute("aria-modal");
    modalEl.style.display = "none";

    // Remove .modal-open from body if present
    document.body.classList.remove("modal-open");

    // Remove any modal backdrop elements inserted by frameworks
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

    // Add/remove shadow classes
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
 * Position dropdown menu using fixed positioning with smart scroll detection
 */
function positionDropdown(toggle, menu) {
    const toggleRect = toggle.getBoundingClientRect();
    const menuWidth = 220; // min-width from CSS
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

    // Calculate available space
    const spaceBelow = viewportHeight - toggleRect.bottom - padding;
    const spaceAbove = toggleRect.top - padding;
    const maxAllowedHeight = viewportHeight - 100; // 50px padding from top and bottom

    // Determine if we need scrolling
    let needsScroll = false;
    let finalHeight = menuHeight;

    if (menuHeight > maxAllowedHeight) {
        needsScroll = true;
        finalHeight = maxAllowedHeight;
    }

    // Calculate vertical position
    if (spaceBelow >= finalHeight || spaceBelow > spaceAbove) {
        // Show below
        top = toggleRect.bottom + padding;

        // If menu would go off bottom, adjust
        if (top + finalHeight > viewportHeight - padding) {
            top = viewportHeight - finalHeight - padding;
        }
    } else {
        // Show above
        top = toggleRect.top - finalHeight - padding;

        // If menu would go off top, adjust
        if (top < padding) {
            top = padding;
        }
    }

    // Calculate horizontal position (align to right of toggle)
    left = toggleRect.right - menuWidth;

    // Ensure menu stays within viewport horizontally
    if (left < padding) {
        left = padding;
    } else if (left + menuWidth > viewportWidth - padding) {
        left = viewportWidth - menuWidth - padding;
    }

    // Apply position
    menu.style.top = `${top}px`;
    menu.style.left = `${left}px`;

    // Apply max-height only if needed
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
 * Initialize SweetAlert notifications
 */
function initializeNotifications() {
    // Check if SweetAlert2 is loaded
    if (typeof Swal === "undefined") {
        console.warn("SweetAlert2 not loaded. Loading from CDN...");
        loadSweetAlert();
        return;
    }

    showSessionNotifications();
}

/**
 * Load SweetAlert2 from CDN if not available
 */
function loadSweetAlert() {
    const script = document.createElement("script");
    script.src = "https://cdn.jsdelivr.net/npm/sweetalert2@11";
    script.onload = () => {
        showSessionNotifications();
    };
    document.head.appendChild(script);
}

/**
 * Show session-based notifications
 */
function showSessionNotifications() {
    // Success notification
    const successMessage = document.querySelector("[data-success-message]");
    if (successMessage) {
        const message = successMessage.dataset.successMessage;
        showToast("success", "Berhasil!", message);
    }

    // Error notification
    const errorMessage = document.querySelector("[data-error-message]");
    if (errorMessage) {
        const message = errorMessage.dataset.errorMessage;
        showToast("error", "Gagal!", message);
    }
}

/**
 * Show toast notification
 */
function showToast(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: "top-end",
        timerProgressBar: true,
    });
}

/**
 * Delete report confirmation
 */
function hapusLaporan(id) {
    Swal.fire({
        title: "Hapus Laporan?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        reverseButtons: true,
        focusCancel: true,
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
 * Close dropdowns when clicking outside
 */
document.addEventListener("click", function (event) {
    if (!event.target.closest(".dropdown")) {
        closeAllDropdowns();
    }
});

// Export functions for use in blade templates
window.hapusLaporan = hapusLaporan;

/**
 * Initialize table scroll behavior
 */
function initializeTableScroll() {
    const tableWrapper = document.querySelector(".modern-table-wrapper");

    if (!tableWrapper) return;

    // Check if on mobile
    const isMobile = window.innerWidth <= 768;

    if (isMobile) {
        // Show scroll hint on mobile
        showMobileScrollHint(tableWrapper);
    }

    // Hide hint when user starts scrolling
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
    // Check if already shown
    if (sessionStorage.getItem("mobileScrollHintShown")) return;

    // Check if table is actually scrollable
    if (wrapper.scrollWidth <= wrapper.clientWidth) return;

    // Show hint
    setTimeout(() => {
        wrapper.classList.add("show-hint");

        // Hide after 3 seconds
        setTimeout(() => {
            wrapper.classList.remove("show-hint");
            sessionStorage.setItem("mobileScrollHintShown", "true");
        }, 3000);
    }, 500);
}
