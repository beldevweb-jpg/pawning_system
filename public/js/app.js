document.addEventListener("DOMContentLoaded", () => {

    /* =====================
       เปลี่ยนหน้า
    ===================== */
    window.goPage = function(page) {
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

        radios.forEach(radio => {
            radio.addEventListener("change", () => {
                if (otherRadio.checked) {
                    input.disabled = false;
                } else {
                    input.disabled = true;
                    input.value = "";
                }
            });
        });
    }

    toggleOther("selltype", "pawn-other", "typesell-other-input");
    toggleOther("product_type", "type-other", "type-other-input");
    toggleOther("brand", "brand-other", "brand-other-input");

    /* =====================
       Search validation
    ===================== */
    const form = document.getElementById("searchForm");
    const phone = document.getElementById("phone");
    const serial = document.getElementById("serial");

    if (form && phone && serial) {
        form.addEventListener("submit", (e) => {
            const phoneVal = phone.value.trim();
            const serialVal = serial.value.trim();

            if (!phoneVal && !serialVal) {
                alert("กรุณากรอกเบอร์โทร หรือ หมายเลขเครื่อง อย่างใดอย่างหนึ่ง");
                e.preventDefault();
                return;
            }

            if (phoneVal && serialVal) {
                alert("กรุณากรอกเพียงช่องเดียวเท่านั้น");
                e.preventDefault();
                return;
            }
        });
    }

});