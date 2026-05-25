document.addEventListener("DOMContentLoaded", () => {

    /* =====================
        1. เปลี่ยนหน้า (Navigation)
    ===================== */
    window.goPage = function (page) {
        window.location.href = page;
    };

    /* =====================
        2. ฟังก์ชัน Toggle Input "อื่นๆ" (Generic Function)
    ===================== */
    function toggleOther(groupName, otherRadioId, inputId) {

        const radios = document.querySelectorAll(`input[name="${groupName}"]`);
        const otherRadio = document.getElementById(otherRadioId);
        const input = document.getElementById(inputId);

        if (!radios.length || !otherRadio || !input) return;

        const update = () => {

            if (otherRadio.checked) {

                input.disabled = false;

            } else {

                input.disabled = true;
                input.value = "";

            }
        };

        radios.forEach(radio => {
            radio.addEventListener("change", update);
        });

        update();
    }

    // === เรียกใช้ Toggle ช่องอื่นๆ ===
    // 1. สถานะ (วาง/ต่อ/ไถ่/อื่นๆ)
    toggleOther("other_type", "pawn-other", "typesell-other-input");
    // 2. ประเภทสินค้า (มือถือ/Tablet/อื่นๆ)
    toggleOther("type_category", "type-other", "type-other-input");
    // 3. ยี่ห้อ (iPhone/.../อื่นๆ)
    toggleOther("brand", "brand-other", "brand-other-input");
    // 4. อื่นๆ ของ กรอกรเพิ่มเติม 
    toggleOther("action_type", "action-other", "action-other-input");


    /* =====================
        3. ฟังก์ชันซ่อน/แสดงช่อง "รุ่น" (Model)
       (แสดงเฉพาะเมื่อเลือก มือถือ หรือ Tablet)
    ===================== */
    function handleModelVisibility() {
        const categoryRadios = document.querySelectorAll('input[name="type_category"]');
        const modelWrapper = document.getElementById('model-wrapper');
        let selectedValue = "";

        if (!modelWrapper) return;

        // หาค่าที่ถูกเลือก
        categoryRadios.forEach(radio => {
            if (radio.checked) selectedValue = radio.value;
        });

        // ตรวจสอบเงื่อนไข
        if (selectedValue === "phone" || selectedValue === "Tablet") {
            modelWrapper.classList.remove('hidden');
        } else {
            modelWrapper.classList.add('hidden');
            // ล้างค่าในช่องรุ่นเมื่อถูกซ่อน
            modelWrapper.querySelectorAll('input[type="text"]').forEach(input => {
                input.value = "";
            });
            modelWrapper.querySelectorAll('input[type="text"]').forEach(input => {
                input.value = "";
            });
        }
    }

    // ผูก Event Listener ให้กับ Radio ประเภทสินค้า
    const typeRadios = document.querySelectorAll('input[name="type_category"]');
    typeRadios.forEach(radio => {
        radio.addEventListener('change', handleModelVisibility);
    });

    // เรียกทำงานครั้งแรกตอนโหลดหน้า (กรณี Validation Error แล้วเด้งกลับมา)
    handleModelVisibility();


    /* =====================
        4. Search validation (ตรวจสอบฟอร์มค้นหา)
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
        5. Confirm ก่อน submit
    ===================== */
    const submitBtn = document.getElementById("btn-submit");
    const mainForm = document.querySelector("form");

    if (submitBtn && mainForm) {
        submitBtn.addEventListener("click", (e) => {
            // ใช้ type="button" ที่ปุ่ม submit หรือเช็ค e.preventDefault()
            // หรือถ้าปุ่มเป็น type="submit" ให้ return true/false ใน onsubmit

            // ในที่นี้ถ้ากด Cancel ให้หยุดการส่งข้อมูล
            if (!confirm("ยืนยันการทำรายการหรือไม่ ?")) {
                e.preventDefault();
            }
        });
    }

    /* =====================
        6. Clear form
    ===================== */
    const clearBtn = document.getElementById("clearForm");

    if (clearBtn) {
        clearBtn.addEventListener("click", () => {
            document.querySelectorAll("input").forEach(input => {
                // ล้างค่า text, number, etc.
                if (input.type !== "submit" && input.type !== "button" && input.type !== "hidden") {
                    input.value = "";
                }

                // ล้างค่า radio, checkbox
                if (input.type === "radio" || input.type === "checkbox") {
                    input.checked = false;
                }

                // Reset disabled state (ถ้าจำเป็น)
                if (input.classList.contains('input') && input.disabled === false && input.name.includes('_other')) {
                    input.disabled = true;
                }
            });

            // Re-check visibility logic
            handleModelVisibility();
        });
    }

    /* =====================
        7. ถ่าย / อัปโหลดรูป + preview (จำกัด 2 รูป)
    ===================== */
    const imageInput = document.getElementById("idcard-image");
    const preview = document.getElementById("preview");

    if (imageInput && preview) {
        imageInput.addEventListener("change", () => {
            preview.innerHTML = "";

            const files = Array.from(imageInput.files).slice(0, 2);

            files.forEach(file => {
                const img = document.createElement("img");
                img.src = URL.createObjectURL(file);
                img.style.width = "120px";
                img.style.borderRadius = "8px";
                img.style.objectFit = "cover";
                preview.appendChild(img);
            });
        });
    }

});

/* =====================
    8. รูปสินค้า สูงสุด 4 รูป
===================== */

const productInput =
    document.getElementById("product-images-behind-input");

const productPreview =
    document.getElementById("preview-product");

let filesArray = [];

if (productInput && productPreview) {

    productInput.addEventListener("change", function (e) {

        const newFiles = Array.from(e.target.files);

        if ((filesArray.length + newFiles.length) > 4) {

            alert("อัปโหลดได้ไม่เกิน 4 รูป");
            return;
        }

        newFiles.forEach(file => {

            filesArray.push(file);

            const div = document.createElement("div");

            const img = document.createElement("img");

            img.src = URL.createObjectURL(file);

            img.style.width = "100px";

            const btn = document.createElement("button");

            btn.type = "button";
            btn.innerText = "ลบ";

            btn.onclick = function () {

                filesArray =
                    filesArray.filter(f => f !== file);

                div.remove();

                updateInputFiles();
            };

            div.appendChild(img);
            div.appendChild(btn);

            productPreview.appendChild(div);
        });

        updateInputFiles();
    });

    function updateInputFiles() {

        const dataTransfer = new DataTransfer();

        filesArray.forEach(file => {
            dataTransfer.items.add(file);
        });

        productInput.files = dataTransfer.files;
    }
}

