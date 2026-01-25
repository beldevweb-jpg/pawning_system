document.addEventListener("DOMContentLoaded", () => {

  // เปลี่ยนหน้า
  window.goPage = function(page) {
    window.location.href = page;
  };

  // ฟังก์ชัน toggle input เมื่อเลือก "อื่นๆ"
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

  // ใช้งาน
  toggleOther("selltype", "pawn-other", "typesell-other-input");
  toggleOther("product_type", "type-other", "type-other-input");
  toggleOther("brand", "brand-other", "brand-other-input");

});
