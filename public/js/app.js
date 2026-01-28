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

    update(); // โหลดหน้าแล้วยังทำงานถูก
  }

  // === toggle ช่องอื่นๆ ===
  toggleOther("subcategories", "pawn-other", "typesell-other-input");
  toggleOther("type_category", "type-other", "type-other-input");
  toggleOther("brand", "brand-other", "brand-other-input");

  /* =====================
     Search validation
  ===================== */
  const searchForm = document.getElementById("searchForm");
  const tax = document.getElementById("tax_number");
  const serial = document.getElementById("serial");

  if (searchForm && tax && serial) {
    searchForm.addEventListener("submit", (e) => {
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

  /* =====================
     Confirm ก่อน submit
  ===================== */
  const submitBtn = document.getElementById("btn-submit");
  const mainForm = document.querySelector("form");

  if (submitBtn && mainForm) {
    submitBtn.addEventListener("click", (e) => {
      if (!confirm("ยืนยันการทำรายการหรือไม่ ?")) {
        e.preventDefault();
      }
    });
  }

  /* =====================
     Clear form
  ===================== */
  const clearBtn = document.getElementById("clearForm");

  if (clearBtn) {
    clearBtn.addEventListener("click", () => {
      document.querySelectorAll("input").forEach(input => {
        input.value = "";
        if (input.type === "radio" || input.type === "checkbox") {
          input.checked = false;
        }
        if (input.disabled) {
          input.disabled = true;
        }
      });
    });
  }

});
