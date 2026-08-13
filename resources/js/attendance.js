document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const menuButton = document.getElementById("menuButton");
    const overlay = document.getElementById("mobileOverlay");
    const toast = document.getElementById("toast");

    const showToast = (message) => {
        toast.textContent = message;
        toast.classList.add("show");

        clearTimeout(window.__toastTimer);
        window.__toastTimer = setTimeout(() => {
            toast.classList.remove("show");
        }, 2200);
    };

    const closeMenu = () => {
        sidebar.classList.remove("open");
        overlay.classList.remove("active");
    };

    menuButton?.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        overlay.classList.toggle("active");
    });

    overlay?.addEventListener("click", closeMenu);

    document.querySelectorAll(".attendance-status").forEach((button) => {
        button.addEventListener("click", () => {
            const row = button.closest(".student-row");
            const feedbackToggle = row.querySelector(".toggle");
            const feedbackLabel = row.querySelector(".feedback-label");
            const present = button.dataset.present === "true";

            if (present) {
                button.dataset.present = "false";
                button.textContent = "Absent";
                button.classList.remove("present");
                button.classList.add("absent");

                feedbackToggle.disabled = true;
                feedbackToggle.classList.remove("on");
                feedbackToggle.classList.add("off");
                feedbackToggle.dataset.enabled = "false";
                feedbackLabel.textContent = "Feedback Disabled";
                feedbackLabel.classList.remove("enabled");
                feedbackLabel.classList.add("disabled");
            } else {
                button.dataset.present = "true";
                button.textContent = "Present";
                button.classList.remove("absent");
                button.classList.add("present");

                feedbackToggle.disabled = false;
            }
        });
    });

    document.querySelectorAll(".toggle").forEach((toggle) => {
        toggle.addEventListener("click", () => {
            if (toggle.disabled) return;

            const label = toggle.parentElement.querySelector(".feedback-label");
            const enabled = toggle.dataset.enabled === "true";

            toggle.dataset.enabled = String(!enabled);
            toggle.classList.toggle("on", !enabled);
            toggle.classList.toggle("off", enabled);

            label.textContent = !enabled ? "Enabled" : "Disabled";
            label.classList.toggle("enabled", !enabled);
            label.classList.toggle("disabled", enabled);
        });
    });

    document.getElementById("markPresentBtn")?.addEventListener("click", () => {
        document.querySelectorAll(".attendance-status").forEach((button) => {
            button.dataset.present = "true";
            button.textContent = "Present";
            button.classList.remove("absent");
            button.classList.add("present");

            const row = button.closest(".student-row");
            const toggle = row.querySelector(".toggle");
            toggle.disabled = false;
        });

        showToast("All students marked present");
    });

    document.getElementById("enableFeedbackBtn")?.addEventListener("click", () => {
        document.querySelectorAll(".student-row").forEach((row) => {
            const attendance = row.querySelector(".attendance-status");
            const toggle = row.querySelector(".toggle");
            const label = row.querySelector(".feedback-label");

            if (attendance.dataset.present === "true") {
                toggle.disabled = false;
                toggle.dataset.enabled = "true";
                toggle.classList.add("on");
                toggle.classList.remove("off");
                label.textContent = "Enabled";
                label.classList.add("enabled");
                label.classList.remove("disabled");
            }
        });

        showToast("Feedback enabled for all present students");
    });

    document.getElementById("saveAttendanceBtn")?.addEventListener("click", () => {
        const students = [...document.querySelectorAll(".student-row")].map((row) => ({
            roll: row.dataset.roll,
            present: row.querySelector(".attendance-status").dataset.present === "true",
            feedbackEnabled: row.querySelector(".toggle").dataset.enabled === "true"
        }));

        console.log("Attendance payload:", {
            course: document.getElementById("courseSelect").value,
            topic: document.getElementById("topicSelect").value,
            date: document.getElementById("attendanceDate").value,
            lecture: document.getElementById("lectureSelect").value,
            students
        });

        showToast("Attendance saved successfully");
    });
});
