document.addEventListener("DOMContentLoaded", () => {

    /* =====================
       เปลี่ยนหน้า
    ===================== */
    window.goPage = function (page) {
        window.location.href = page;
    };

    /* =====================
       toggle input "อื่นๆ"
    ===================== */
    function toggleOther(groupName, otherRadioId, inputId) {
        const radios = document.querySelectorAll(`input[name="${groupName}"]`);
        const otherRadio = document.getElementById(otherRadioId);
        const input = document.getElementById(inputId);

        if (!radios.length || !otherRadio || !input) return;

        const update = () => {
            if (otherRadio.checked) {
                input.disabled = false;
                input.focus();
            } else {
                input.disabled = true;
                input.value = "";
            }
        };

        radios.forEach(radio => {
            radio.addEventListener("change", update);
        });

        update(); // ✅ โหลดหน้า / old() แล้วยังทำงาน
    }

    // === ตรงกับ HTML ที่คุณส่งมา ===
    toggleOther("type_serve", "type-serve-other", "type-serve-input");
    toggleOther("type_category", "type-category-other", "type-category-input");

    /* =====================
       Search validation
    ===================== */
    const form = document.getElementById("searchForm");
    const tax = document.getElementById("tax_number");
    const serial = document.getElementById("serial");

    if (form && tax && serial) {
        form.addEventListener("submit", (e) => {
            const taxVal = tax.value.trim();
            const serialVal = serial.value.trim();

            if (!taxVal && !serialVal) {
                alert("กรุณากรอกบัตรประชาชน หรือ หมายเลขเครื่อง อย่างใดอย่างหนึ่ง");
                e.preventDefault();
                return;
            }

            if (taxVal && serialVal) {
                alert("กรุณากรอกเพียงช่องเดียวเท่านั้น");
                e.preventDefault();
                return;
            }
        });
    }


});


const form = document.querySelector('form');
const btn = document.getElementById('btn-submit');

btn.addEventListener('click', function (e) {
    if (!confirm('ยืนยันการทำรายการหรือไม่ ?')) {
        e.preventDefault();
    }
});
