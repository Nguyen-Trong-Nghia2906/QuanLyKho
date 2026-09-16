
let timer = null;
let stt = 0;
let idCanXoa = null;
$(document).ready(function () {
    $('#form_dangnhap').submit(function (e) {
        e.preventDefault();
        let data = $(this).serialize();
        $.ajax({
            url: "model/dangnhap.php",
            type: "POST",
            data: data,
            success: function (res) {
                if (res.trim() == "success") {
                    window.location.href = '?v=vattu';
                }
                else {
                    $('#text_dangnhap').html("Tài khoản hoặc mật khẩu không đúng")
                }

            }
        })
    })

    $(document).on("click", ".open-details", function () {
        const row = $(this).closest("tr");
        row.next(".details").toggle();
        const icon = $(this);
        icon.text(icon.text().trim() === '▶' ? '▼' : '▶');
    });

    // Lưu giá trị ban đầu của các ô input
    const originalData = {
        tenhang: $('textarea').val(),

        mahang: $('input:eq(0)').val(),
        vitri: $('input:eq(1)').val(),
        tenkho: $('input:eq(2)').val(),
        dvt: $('input:eq(4)').val(),
        giatri: $('input:eq(5)').val(),
    };


    $('#btn_save').click(function () {
        const currentData = {
            tenhang: $('textarea').val(),

            mahang: $('input:eq(0)').val(),
            vitri: $('input:eq(1)').val(),
            tenkho: $('input:eq(2)').val(),
            dvt: $('input:eq(4)').val(),
            giatri: $('input:eq(5)').val(),


        };

        const hasChanged = Object.keys(originalData).some(key => originalData[key] != currentData[key]);

        if (hasChanged) {
            const id = new URLSearchParams(window.location.search).get("id");
            $.ajax({
                url: 'model/updateVattu.php',
                method: 'POST',
                data: {
                    id: id,
                    ...currentData
                },
                success: function (res) {
                    showToast('Cập nhật thành công', 'success');
                    // window.location.href = '?v=vattu';
                },
                error: function () {
                    alert("Cập nhật thất bại");
                }
            });
        } else {
            // history.back(); // Không thay đổi gì → quay về luôn
        }
    });

    function searchVattu() {
        clearTimeout(timer);

        timer = setTimeout(function () {
            let keyword = $('#search').val().trim();
            let field = $('#select_search').val(); // "Tên", "Vị trí", "Kho"

            // Map từ tiếng Việt sang tên cột trong CSDL
            let columnMap = {
                "Tên": "tenhang",
                "Vị trí": "vitri",
                "Kho": "tenkho"
            };

            $.ajax({
                url: "model/searchVattu.php",
                method: "POST",
                data: {
                    keyword: keyword,
                    column: columnMap[field] || "tenhang"
                },
                success: function (data) {
                    // // Xử lý hiển thị dữ liệu bảng (desktop)
                    // let htmlTable = '';
                    // let htmlMobile = '';
                    //     $.each(data, function (i, item) {

                    //         htmlTable += `
                    //     <tr data-id="${item.id}" >


                    //       <td>${item.tenhang}</td>
                    //       <td>${item.vitri ?? 'Chưa cập nhật'}</td>
                    //       <td>${item.dvt}</td>
                    //       <td>${item.soluong}</td>
                    //       <td>${item.tenkho}</td>


                    //       <td class="text-nowrap">
                    //             <button data-id="${item.id}" data-bs-toggle="modal" data-bs-target="#lichSuXuatModal"
                    //             class="btn_lichsu btn btn-sm btn-outline-success" title="Lịch sử">
                    //             <i class="bi bi-clock-history"></i>
                    //             </button>

                    //             <a href="?v=chitiet_vattu&id=${item.id}" class="btn btn-sm btn-outline-primary" title="Chi tiết">
                    //             <i class="bi bi-pencil-square"></i>
                    //             </a>

                    //             <button data-id="${item.id}" class="btn_remove btn btn-sm btn-outline-danger" title="Xóa">
                    //             <i class="bi bi-trash"></i>
                    //             </button>
                    //         </td>


                    //     </tr>
                    //   `;

                    //         htmlMobile += `
                    //     <a style="color:#000; text-decoration:none" href="?v=chitiet_vattu&id=${item.id}">
                    //       <div class="card-vattu">

                    //         <div class="info">
                    //           <div class="name">${item.tenhang}</div>
                    //           <div class="location">${item.vitri ?? 'Chưa cập nhật'}</div>
                    //           <div class="location">${item.tenkho}</div>
                    //         </div>
                    //         <div class="qty">SL: ${item.soluong}</div>
                    //       </div>
                    //     </a>
                    //   `;
                    //     });

                    $('#bang_vattu').html(data);
                    // $('#vattu-container-mobile').html(htmlMobile);
                }
            });
        }, 350)

    }



    function searchVattuMobile() {
        let keyword = $('#searchMobile').val().trim();
        let field = $('#select_search_mobile').val(); // "Tên", "Vị trí", "Kho"

        // Map từ tiếng Việt sang tên cột trong CSDL
        let columnMap = {
            "Tên": "tenhang",
            "Vị trí": "vitri",
            "Kho": "tenkho"
        };

        $.ajax({
            url: "model/searchVattuMobile.php",
            method: "POST",
            data: {
                keyword: keyword,
                column: columnMap[field] || "tenhang"
            },
            success: function (data) {

                $('#bang_vattu_mobile').html(data);
                // $('#vattu-container-mobile').html(htmlMobile);
            }
        });
    }

    // Gọi khi gõ phím hoặc chọn lại điều kiện
    $('#search').on('input', searchVattu);
    $('#select_search').on('change', searchVattu);


    // Gọi khi gõ phím hoặc chọn lại điều kiện
    $('#searchMobile').on('input', searchVattuMobile);
    $('#select_search_mobile').on('change', searchVattuMobile);


    // Khi bấm nút thêm mới (cả desktop & mobile)
    $(".btn_add, .btn_add_mobile").click(function (e) {
        e.preventDefault();
        $("#modalThemMoi").modal("show");

    });

    // Gửi form thêm mới
    $("#form-them-moi").submit(function (e) {
        e.preventDefault();

        let formData = $(this).serialize();
        $.ajax({
            url: "model/addVattu.php",
            type: "POST",
            data: formData,
            success: function (res) {
                // Tắt modal
                $("#modalThemMoi").modal("hide");



                $("#bang-vattu tbody").prepend(res); // Thêm lên đầu bảng

                showToast('Thêm mới thành công', 'success');
                $("#form-them-moi")[0].reset(); // Reset form
            }
        });
    });


    // Khi click nút Xóa
    $(document).on('click', '.btn_remove', function () {
        idCanXoa = $(this).data('id'); // Lưu ID để xóa
        $('#modalXacNhanXoa').modal('show');
    });

    // Khi bấm xác nhận xóa
    $('#btnXacNhanXoa').on('click', function () {
        if (!idCanXoa) return;

        $.ajax({
            url: 'model/deleteVattu.php',
            method: 'POST',
            data: { id: idCanXoa },
            success: function (response) {
                if (response == 'success') {
                    $('tr[data-id="' + idCanXoa + '"]').remove();
                    $('#modalXacNhanXoa').modal('hide');
                    showToast('Xóa thành công', 'success');
                } else {
                    alert('Xóa thất bại. Vui lòng thử lại!');
                }
            },
            error: function () {
                alert('Có lỗi xảy ra khi gửi yêu cầu xóa.');
            }
        });
        $('#modalXacNhanXoa').modal('hide');
    });


    $('#btnXacNhanXoaMobile').on('click', function () {
        const id = $('.btn_remove').data('id');

        if (!id) return;

        $.ajax({
            url: 'model/deleteVattu.php',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response === 'success') {
                    // Đóng modal
                    $('#modalXacNhanXoa').modal('hide');

                    // Trở về trang danh sách (back)
                    setTimeout(() => {
                        window.location.href = '?v=vattu';
                    }, 300); // Delay nhẹ để tránh xung đột UI
                } else {
                    alert('Xóa thất bại. Vui lòng thử lại!');
                }
            },
            error: function () {
                alert('Có lỗi xảy ra khi gửi yêu cầu xóa.');
            }
        });
    });


    $(document).on('click', '.btn_lichsu', function () {
        const id = $(this).data('id');
        $('#lichSuContent').html('Đang tải dữ liệu...');

        $.ajax({
            url: 'model/lichSuVatTu.php',
            type: 'POST',
            data: { id_vattu: id },
            success: function (res) {
                $('#lichSuContent').html(res);
            },
            error: function () {
                $('#lichSuContent').html('<div class="text-danger">Lỗi khi tải dữ liệu</div>');
            }
        });
    });


    $(document).on('click', '.btn_thongke', function () {
        const id = $(this).data('id');
        $('#thongKeContent').html('Đang tải dữ liệu...');

        $.ajax({
            url: 'model/thongKeNhaMay.php',
            type: 'POST',
            data: { id_vattu: id },
            success: function (res) {
                $('#thongKeContent').html(res);
            },
            error: function () {
                $('#thongKeContent').html('<div class="text-danger">Lỗi khi tải dữ liệu</div>');
            }
        });
    });


    $(document).on("contextmenu", ".card-vattu", function (e) {
        e.preventDefault();
        return false;
    });
    let pressTimer;

    $(document).on("mousedown touchstart", ".card-vattu", function (e) {
        const card = $(this); // lưu lại this

        pressTimer = setTimeout(function () {
            // Lấy id từ card
            let id = card.data("id");
            $('#lichSuContent').html('Đang tải dữ liệu...');
            //   alert(id)

            // Gọi AJAX lấy dữ liệu lịch sử (ví dụ)
            $.ajax({
                url: "model/lichSuVattuMobile.php",
                method: "POST",
                data: { id_vattu: id },
                success: function (data) {
                    $('#lichSuMobileContent').html(data);
                    $("#lichSuMobileModal").modal("show");
                }
            });

        }, 800); // giữ 0.8s sẽ hiện modal
    }).on("mouseup mouseleave touchend", function () {
        clearTimeout(pressTimer);
    });



    let vtDaChon = new Set();



    $("#tim-vattu-nhap").on("input", function () {
        let keyword = $(this).val().trim();
        if (keyword.length < 2) {
            $("#goi-y-vattu").addClass("d-none").empty();
            return;
        }

        $.post("model/search.php", {
            keyword: keyword,
            column: "tenhang"
        }, function (data) {
            let list = JSON.parse(data);
            let html = "";
            list.forEach(vt => {
                if (!vtDaChon.has(vt.id)) {
                    html += `
          <div class="chon-vattu-nhap" 
              data-ma="${vt.mahang}"
              data-id="${vt.id}" 
              data-ten="${vt.tenhang}" 
              data-dvt="${vt.dvt}" 
              data-slk="${vt.soluong}">
            <strong>${vt.tenhang}</strong><br>
            <small>${vt.mahang}</small><br>
            <small>${vt.dvt} | SL kho: ${vt.soluong}</small>
          </div>`;
                }
            });

            $("#goi-y-vattu").html(html || '<div class="px-2 text-muted">Không tìm thấy</div>').removeClass("d-none");
        });
    });

    // Khi chọn vật tư, thêm vào bảng
    $(document).on("click", ".chon-vattu-nhap", function () {
        let ma = $(this).data("ma");
        let id = $(this).data("id");
        let ten = $(this).data("ten");
        let dvt = $(this).data("dvt");
        let slk = parseFloat($(this).data("slk"));

        // if (vtDaChon.has(id)) return;

        if ($("#ds-vattu-da-chon tr[data-id='" + id + "']").length > 0) {
            showToast('Vật tư này đã được thêm!', 'warning');
            return; // Dừng luôn
        }


        stt++;
        let html = `
      <tr data-id="${id}">
        <td>${stt}</td>
        <td>${ma}<input type="hidden" name="idvt[]" value="${id}"></td>
        <td>${ten}</td>
        <td>${dvt}</td>
        <td>${slk}<input type="hidden" class="sl-kho" value="${slk}"></td>
        <td><input name="soluong[]" type="number" min="0" step="0.01" class="form-control sl-xuat" required></td>
        <td><button type="button" class="btn btn-sm btn-danger xoa-vattu">X</button></td>
      </tr>
    `;
        $("#ds-vattu-da-chon").append(html);
        vtDaChon.add(id);
        $("#goi-y-vattu").addClass("d-none").empty();
        $("#tim-vattu").val('');
    });


    $("#tim-vattu").on("input", function () {
        let keyword = $(this).val().trim();
        if (keyword.length < 2) {
            $("#goi-y-vattu").addClass("d-none").empty();
            return;
        }

        $.post("model/search.php", {
            keyword: keyword,
            column: "tenhang"
        }, function (data) {
            let list = JSON.parse(data);
            let html = "";
            list.forEach(vt => {
                if (!vtDaChon.has(vt.mahang)) {
                    html += `
          <div class="chon-vattu" 
              data-ma="${vt.mahang}"
              data-id="${vt.id}" 
              data-ten="${vt.tenhang}" 
              data-dvt="${vt.dvt}" 
              data-slk="${vt.soluong}">
            <strong>${vt.tenhang}</strong><br>
            <small>${vt.mahang}</small><br>
            <small>${vt.dvt} | SL kho: ${vt.soluong}</small>
          </div>`;
                }
            });

            $("#goi-y-vattu").html(html || '<div class="px-2 text-muted">Không tìm thấy</div>').removeClass("d-none");
        });
    });

    $(document).on("click", ".chon-vattu", function () {
        let ma = $(this).data("ma");
        let id = $(this).data("id");
        let ten = $(this).data("ten");
        let dvt = $(this).data("dvt");
        let slk = parseFloat($(this).data("slk"));

        // if (vtDaChon.has(id)) return;

        if ($("#ds-vattu-da-chon tr[data-id='" + id + "']").length > 0) {
            showToast('Vật tư này đã được thêm!', 'warning');
            return; // Dừng luôn
        }

        stt++;

        let html = `
      <tr data-id="${id}">
        <td>${stt}</td>
        <td>${ma}<input type="hidden" name="idvt[]" value="${id}"></td>
        <td>${ten}</td>
        <td>${dvt}</td>
        <td>${slk}<input type="hidden" class="sl-kho" value="${slk}"></td>
        <td><input name="soluong[]" type="number" min="0" step="0.01" max="${slk}" class="form-control sl-xuat" required></td>
        <td><input name="mucdich[]" type="text" class="form-control" ></td>
        <td><button type="button" class="btn btn-sm btn-danger xoa-vattu">X</button></td>
      </tr>
    `;
        $("#ds-vattu-da-chon").append(html);
        vtDaChon.add(id);
        $("#goi-y-vattu").addClass("d-none").empty();
        $("#tim-vattu").val('');
    });

    // Xóa vật tư khỏi danh sách
    $(document).on("click", ".xoa-vattu", function () {
        const row = $(this).closest("tr");
        const id = row.data("id");
        vtDaChon.delete(id);
        row.remove();
    });


    $("#form-phieu-nhap").submit(function (e) {
        e.preventDefault(); // Ngăn gửi form mặc định

        let hopLe = true;

        // Kiểm tra số phiếu
        const ncc = $('input[name="nhacungcap"]');
        if (!ncc.val().trim()) {
            hopLe = false;
            ncc.focus();
            return;
        }

        // Kiểm tra có chọn vật tư chưa
        const rows = $("#ds-vattu-da-chon tr");
        if (rows.length == 0) {
            $('#tim-vattu').focus(); // quay lại ô tìm vật tư
            return;
        }

        // Nếu hợp lệ thì gửi
        $.post("model/taoPhieuNhap.php", $(this).serialize(), function (res) {
            location.href = '?v=phieunhap'
            showToast('Thêm mới thành công', 'success');
        });
    });

    $("#form-phieu-xuat").submit(function (e) {
        e.preventDefault(); // Ngăn gửi form mặc định

        let hopLe = true;

        // Kiểm tra số phiếu
        const soPhieu = $('input[name="so_phieu"]');
        if (!soPhieu.val().trim()) {
            hopLe = false;
            soPhieu.focus();
            return;
        }

        // Kiểm tra tổ
        const to = $('select[name="to"]');
        if (to.val() == "0") {
            hopLe = false;
            to.focus();
            return;
        }

        // Kiểm tra người đề nghị
        const nguoi = $('select[name="nguoi_de_nghi"]');
        if (nguoi.val() == "0") {
            hopLe = false;
            nguoi.focus();
            return;
        }

        // Kiểm tra có chọn vật tư chưa
        const rows = $("#ds-vattu-da-chon tr");
        if (rows.length == 0) {
            $('#tim-vattu').focus(); // quay lại ô tìm vật tư
            return;
        }

        // Kiểm tra số lượng từng vật tư
        let hasError = false;
        rows.each(function () {
            const slKho = parseFloat($(this).find(".sl-kho").val());
            const slXuatInput = $(this).find(".sl-xuat");
            const slXuat = parseFloat(slXuatInput.val());

            if (!slXuat || slXuat <= 0 || slXuat > slKho) {
                slXuatInput.focus();
                hasError = true;
                return false;
            }
        });

        if (hasError) return;

        // Nếu hợp lệ thì gửi
        $.post("model/taoPhieuXuat.php", $(this).serialize(), function (res) {
            location.href = '?v=phieuxuat'
            showToast('Thêm mới thành công', 'success');

        });
    });


    $("#form-phieu-ung").submit(function (e) {
        e.preventDefault(); // Ngăn gửi form mặc định

        let hopLe = true;


        // Kiểm tra tổ
        const to = $('select[name="to"]');
        if (to.val() == "0") {
            hopLe = false;
            to.focus();
            return;
        }

        // Kiểm tra người đề nghị
        const nguoi = $('select[name="nguoi_de_nghi"]');
        if (nguoi.val() == "0") {
            hopLe = false;
            nguoi.focus();
            return;
        }

        // Kiểm tra có chọn vật tư chưa
        const rows = $("#ds-vattu-da-chon tr");
        if (rows.length == 0) {
            $('#tim-vattu').focus(); // quay lại ô tìm vật tư
            return;
        }

        // Kiểm tra số lượng từng vật tư
        let hasError = false;
        rows.each(function () {
            const slKho = parseFloat($(this).find(".sl-kho").val());
            const slXuatInput = $(this).find(".sl-xuat");
            const slXuat = parseFloat(slXuatInput.val());

            if (!slXuat || slXuat <= 0 || slXuat > slKho) {
                slXuatInput.focus();
                hasError = true;
                return false;
            }
        });

        if (hasError) return;

        // Nếu hợp lệ thì gửi
        $.post("model/taoPhieuUng.php", $(this).serialize(), function (res) {
            location.href = '?v=phieuung';
            showToast('Thêm mới thành công', 'success');

        });
    });



    //tìm kiếm phiếu xuất
    $('select[name="to"]').on('change', function () {
        let idTo = $(this).val();
        if (idTo == 0) {
            $('select[name="nguoi_de_nghi"]').html('<option value="0"> --- </option>');
            return;
        }

        $.get('model/lay_nhanvien_theo_to.php', { id_to: idTo }, function (data) {
            let nvList = JSON.parse(data);
            let html = '<option value="0"> --- </option>';
            nvList.forEach(nv => {
                html += `<option value="${nv.id}">${nv.hoten}</option>`;
            });
            $('select[name="nguoi_de_nghi"]').html(html);
        });
    });


    $('.toggle-details').on('click', function () {
        const $icon = $(this);
        const $detailsRow = $icon.closest('tr').next('.details-row');

        // Ẩn tất cả dòng chi tiết khác nếu bạn muốn chỉ mở 1 dòng
        $('.details-row').not($detailsRow).hide();
        $('.toggle-details').not($icon).text('▶');

        // Toggle dòng chi tiết tương ứng
        $detailsRow.toggle();
        $icon.text($detailsRow.is(':visible') ? '▼' : '▶');
    });



    //xóa phiếu xuất
    $(document).on('click', '.xoa_phieuxuat', function () {
        idCanXoa = $(this).data('id'); // Lưu ID để xóa
        $('#modalXacNhanXoa').modal('show');
    });

    $('#xacNhanXoaPX').on('click', function () {
        if (!idCanXoa) return;

        $.ajax({
            url: 'model/deletePhieuXuat.php',
            method: 'POST',
            data: { id: idCanXoa },
            success: function (response) {
                if (response == 'success') {
                    let row = $('tr[data-id="' + idCanXoa + '"]');
                    row.next('.details').remove(); // Xóa dòng chi tiết
                    row.remove();                      // Xóa dòng phiếu
                    $('#modalXacNhanXoa').modal('hide');
                    showToast('Xóa thành công', 'success');
                } else {
                    alert('Xóa thất bại. Vui lòng thử lại!');
                }
            },
            error: function () {
                alert('Có lỗi xảy ra khi gửi yêu cầu xóa.');
            }
        });
        $('#modalXacNhanXoa').modal('hide');
    });


    //xóa phiếu nhập
    $(document).on('click', '.xoa_phieunhap', function () {
        idCanXoa = $(this).data('id'); // Lưu ID để xóa
        $('#modalXacNhanXoa').modal('show');
    });

    $('#xacNhanXoaPN').on('click', function () {
        if (!idCanXoa) return;

        $.ajax({
            url: 'model/deletePhieuNhap.php',
            method: 'POST',
            data: { id: idCanXoa },
            success: function (response) {
                if (response == 'success') {
                    let row = $('tr[data-id="' + idCanXoa + '"]');
                    row.next('.details').remove(); // Xóa dòng chi tiết
                    row.remove();                      // Xóa dòng phiếu
                    $('#modalXacNhanXoa').modal('hide');
                    showToast('Xóa thành công', 'success');
                } else {
                    alert('Xóa thất bại. Vui lòng thử lại!');
                }
            },
            error: function () {
                alert('Có lỗi xảy ra khi gửi yêu cầu xóa.');
            }
        });
        $('#modalXacNhanXoa').modal('hide');
    });


    //xóa phiếu ứng
    $(document).on('click', '.xoa_phieuung', function () {
        idCanXoa = $(this).data('id'); // Lưu ID để xóa
        $('#modalXacNhanXoa').modal('show');
    });

    $('#xacNhanXoaPU').on('click', function () {
        if (!idCanXoa) return;

        $.ajax({
            url: 'model/deletePhieuUng.php',
            method: 'POST',
            data: { id: idCanXoa },
            success: function (response) {
                if (response.trim() == 'success') {
                    let row = $('tr[data-id="' + idCanXoa + '"]');
                    row.next('.details').remove(); // Xóa dòng chi tiết
                    row.remove();                      // Xóa dòng phiếu
                    $('#modalXacNhanXoa').modal('hide');
                    showToast('Xóa thành công', 'success');
                } else {
                    alert('Xóa thất bại. Vui lòng thử lại!');
                }
            },
            error: function () {
                alert('Có lỗi xảy ra khi gửi yêu cầu xóa.');
            }
        });
        $('#modalXacNhanXoa').modal('hide');
    });



    //tìm phiếu xuất
    $(document).on('change', '#tim_to', function () {
        let to = $('#tim_to').val();
        let maphieu = $('#tim_maphieu').val();
        let ten_nv = $('#tim_nhanvien').val();
        let ngayxuat = $('#tim_ngayxuat').val();
        let ten_vt = $('#tim_vattu').val();
        $.ajax({
            url: "model/searchPhieuXuat.php",
            type: "POST",
            data: {
                id_to: to,
                ten_nv: ten_nv,
                ngayxuat: ngayxuat,
                ten_vt: ten_vt,
                maphieu: maphieu
            },
            success: function (res) {
                $('tbody').html(res);

            }
        })
    })


    $(document).on('input', '#tim_nhanvien', function () {
        clearTimeout(timer);
let to = $('#tim_to').val();
            let maphieu = $('#tim_maphieu').val();
            let ngayxuat = $('#tim_ngayxuat').val();
            let ten_vt = $('#tim_vattu').val();
        timer = setTimeout(function () {
            
            let ten_nv = $('#tim_nhanvien').val();
            

            $.ajax({
                url: "model/searchPhieuXuat.php",
                type: "POST",
                data: {
                    id_to: to,
                    ten_nv: ten_nv,
                    ngayxuat: ngayxuat,
                    ten_vt: ten_vt,
                    maphieu: maphieu
                },
                success: function (res) {
                    $('tbody').html(res);
                }
            });
        }, 500); // CHỈ tìm sau khi người dùng dừng gõ 350ms
    });

    $(document).on('input', '#tim_maphieu', function () {
        clearTimeout(timer);
let to = $('#tim_to').val();
let ten_nv = $('#tim_nhanvien').val();
            let ngayxuat = $('#tim_ngayxuat').val();
            let ten_vt = $('#tim_vattu').val();
        timer = setTimeout(function () {
            
            let maphieu = $('#tim_maphieu').val();
            

            $.ajax({
                url: "model/searchPhieuXuat.php",
                type: "POST",
                data: {
                    id_to: to,
                    ten_nv: ten_nv,
                    ngayxuat: ngayxuat,
                    ten_vt: ten_vt,
                    maphieu: maphieu
                },
                success: function (res) {
                    $('tbody').html(res);
                }
            });
        }, 500); // CHỈ tìm sau khi người dùng dừng gõ 350ms
    });

    $(document).on('change', '#tim_ngayxuat', function () {
        let to = $('#tim_to').val();
        let maphieu = $('#tim_maphieu').val();
        let ten_nv = $('#tim_nhanvien').val();
        let ngayxuat = $('#tim_ngayxuat').val();
        let ten_vt = $('#tim_vattu').val();
        $.ajax({
            url: "model/searchPhieuXuat.php",
            type: "POST",
            data: {
                id_to: to,
                ten_nv: ten_nv,
                ngayxuat: ngayxuat,
                ten_vt: ten_vt,
                maphieu: maphieu
            },
            success: function (res) {
                $('tbody').html(res);

            }
        })
    });

    $(document).on('keyup', '#tim_vattu', function () {
        clearTimeout(timer);
let to = $('#tim_to').val();
            let maphieu = $('#tim_maphieu').val();
            let ten_nv = $('#tim_nhanvien').val();
            let ngayxuat = $('#tim_ngayxuat').val();
        timer = setTimeout(function () {
            
            let ten_vt = $('#tim_vattu').val();

            $.ajax({
                url: "model/searchPhieuXuat.php",
                type: "POST",
                data: {
                    id_to: to,
                    ten_nv: ten_nv,
                    ngayxuat: ngayxuat,
                    ten_vt: ten_vt,
                    maphieu: maphieu
                },
                success: function (res) {
                    $('tbody').html(res);
                }
            });
        }, 500); // CHỈ tìm sau khi người dùng dừng gõ 350ms
    })


    //tìm kiếm phiếu ứng
    $(document).on('change', '#tim_to_ung', function () {
        let to = $('#tim_to_ung').val();
        let ten_nv = $('#tim_nhanvien_ung').val();
        let ngayxuat = $('#tim_ngayung').val();
        let ten_vt = $('#tim_vattu').val();
        $.ajax({
            url: "model/searchPhieuUng.php",
            type: "POST",
            data: {
                id_to: to,
                ten_nv: ten_nv,
                ngayxuat: ngayxuat,
                ten_vt: ten_vt
            },
            success: function (res) {
                $('tbody').html(res);

            }
        })
    })


    $(document).on('keyup', '#tim_nhanvien_ung', function () {
       clearTimeout(timer);

        timer = setTimeout(function () {
            let to = $('#tim_to_ung').val();
            let ten_nv = $('#tim_nhanvien_ung').val();
            let ngayxuat = $('#tim_ngayung').val();
            let ten_vt = $('#tim_vattu_ung').val();
            $.ajax({
                url: "model/searchPhieuUng.php",
                type: "POST",
                data: {
                    id_to: to,
                    ten_nv: ten_nv,
                    ngayxuat: ngayxuat,
                    ten_vt: ten_vt
                },
                success: function (res) {
                    $('tbody').html(res);

                }
            })
        }, 500); // CHỈ tìm sau khi người dùng dừng gõ 350ms

    });

    $(document).on('change', '#tim_ngayung', function () {
        let to = $('#tim_to_ung').val();
        let ten_nv = $('#tim_nhanvien_ung').val();
        let ngayxuat = $('#tim_ngayung').val();
        let ten_vt = $('#tim_vattu_ung').val();
        $.ajax({
            url: "model/searchPhieuUng.php",
            type: "POST",
            data: {
                id_to: to,
                ten_nv: ten_nv,
                ngayxuat: ngayxuat,
                ten_vt: ten_vt
            },
            success: function (res) {
                $('tbody').html(res);

            }
        })
    });

    $(document).on('keyup', '#tim_vattu_ung', function () {
        clearTimeout(timer);

        timer = setTimeout(function () {
            let to = $('#tim_to_ung').val();
            let ten_nv = $('#tim_nhanvien_ung').val();
            let ngayxuat = $('#tim_ngayung').val();
            let ten_vt = $('#tim_vattu_ung').val();
            $.ajax({
                url: "model/searchPhieuUng.php",
                type: "POST",
                data: {
                    id_to: to,
                    ten_nv: ten_nv,
                    ngayxuat: ngayxuat,
                    ten_vt: ten_vt
                },
                success: function (res) {
                    $('tbody').html(res);

                }
            })
        }, 500); // CHỈ tìm sau khi người dùng dừng gõ 350ms

    })

    //tìm phiếu nhập
    $(document).on('change', '#tim_ngaynhap', function () {
        let ten_ncc = $('#tim_ncc').val();
        let ngaynhap = $('#tim_ngaynhap').val();
        let ten_vt = $('#tim_vattu_nhap').val();
        $.ajax({
            url: "model/searchPhieuNhap.php",
            type: "POST",
            data: {
                ngaynhap: ngaynhap,
                ten_vt: ten_vt,
                ten_ncc: ten_ncc
            },
            success: function (res) {
                $('tbody').html(res);

            }
        })
    });


    $(document).on('keyup', '#tim_vattu_nhap', function () {
        clearTimeout(timer);

        timer = setTimeout(function () {
            let ten_ncc = $('#tim_ncc').val();
            let ngaynhap = $('#tim_ngaynhap').val();
            let ten_vt = $('#tim_vattu_nhap').val();
            $.ajax({
                url: "model/searchPhieuNhap.php",
                type: "POST",
                data: {
                    ngaynhap: ngaynhap,
                    ten_vt: ten_vt,
                    ten_ncc: ten_ncc
                },
                success: function (res) {
                    $('tbody').html(res);

                }
            })
        }, 500); // CHỈ tìm sau khi người dùng dừng gõ 350ms



    });


    $(document).on('keyup', '#tim_ncc', function () {
        clearTimeout(timer);

        timer = setTimeout(function () {
            let ten_ncc = $('#tim_ncc').val();
            let ngaynhap = $('#tim_ngaynhap').val();
            let ten_vt = $('#tim_vattu_nhap').val();
            $.ajax({
                url: "model/searchPhieuNhap.php",
                type: "POST",
                data: {
                    ngaynhap: ngaynhap,
                    ten_vt: ten_vt,
                    ten_ncc: ten_ncc
                },
                success: function (res) {
                    $('tbody').html(res);

                }
            })
        }, 500); // CHỈ tìm sau khi người dùng dừng gõ 350ms
    });




    //nhân viên
    $('#form-them-moi-nhanvien').submit(function (e) {
        e.preventDefault();
        let data = $(this).serialize();
        console.log(data);
        let hopLe = true;

        // Kiểm tra số phiếu
        const hoten = $('input[name="hoten"]');
        if (!hoten.val().trim()) {
            hopLe = false;
            hoten.focus();
            return;
        }

        // Kiểm tra có chọn vật tư chưa
        const to_nhanvien = $('select[name="to_nhanvien"]');
        if (to_nhanvien.val() == 0) {
            to_nhanvien.focus();
            return;
        }

        // Nếu hợp lệ thì gửi
        $.post("model/addNhanVien.php", $(this).serialize(), function (res) {
            $("#modalThemMoi").modal("hide");
            $("#form-them-moi-nhanvien")[0].reset(); // Reset form

            showToast('Tjêm mới thành công', 'success');
            $("#bang_nv").prepend(res); // Thêm lên đầu bảng
        });
    });


    // Khi bấm xác nhận xóa
    $('#btnXacNhanXoaNV').on('click', function () {
        if (!idCanXoa) return;

        $.ajax({
            url: 'model/deleteNhanVien.php',
            method: 'POST',
            data: { id: idCanXoa },
            success: function (response) {
                if (response == 'success') {
                    $('tr[data-id="' + idCanXoa + '"]').remove();
                    $('#modalXacNhanXoa').modal('hide');
                    showToast('Xóa thành công', 'success');
                } else {
                    alert('Xóa thất bại. Vui lòng thử lại!');
                }
            },
            error: function () {
                alert('Có lỗi xảy ra khi gửi yêu cầu xóa.');
            }
        });
        $('#modalXacNhanXoa').modal('hide');
    });


    $(document).on('click', '.btn_edit_nv', function (e) {
        let id = $(this).data('id');

        $.ajax({
            url: 'model/getNhanVien.php',
            method: 'POST',
            data: { id: id },
            success: function (res) {
                $('#form-chinh-sua-nhanvien').html(res);
                $('#modalChinhSua').modal('show');
            },

        });

    });

    $('#form-chinh-sua-nhanvien').submit(function (e) {
        e.preventDefault();
        let data = $(this).serialize();

        let id_nv = $('#edit_id_nv').val();           // ID nhân viên
        let hoten = $('#edit_hoten').val();
        let sdt = $('#edit_sdt').val();
        let tento = $('#edit_id_to option:selected').text(); // Tên tổ hiển thị (từ dropdown)
        $.ajax({
            url: 'model/updateNhanVien.php',
            method: 'POST',
            data: data,
            success: function (res) {
                if (res.trim() === "success") {
                    // 1. Đóng modal
                    $('#modalChinhSua').modal('hide');

                    // 2. Hiển thị thông báo
                    showToast('Cập nhật thành công', 'success');

                    // 3. Cập nhật dòng tương ứng trong bảng
                    let row = $(`tr[data-id='${id_nv}']`);
                    row.find('td:eq(0)').text(hoten);     // Cột họ tên
                    row.find('td:eq(1)').text(tento);     // Cột tổ
                    row.find('td:eq(2)').text(sdt);       // Cột sdt

                } else {
                    showToast('Cập nhật thất bại', 'error');
                }

            },
        })
    });


    $('#tim_tennv').keyup(function (e) {
        let hoten = $('#tim_tennv').val();
        let to = $('#tim_tonv').val();
        $.ajax({
            url: 'model/searchNhanVien.php',
            method: 'POST',
            data: {
                hoten: hoten,
                to: to
            },
            success: function (res) {
                $('#bang_nv').html(res);
            },

        });

    });


    $('#tim_tonv').change(function (e) {
        let hoten = $('#tim_tennv').val();
        let to = $('#tim_tonv').val();
        $.ajax({
            url: 'model/searchNhanVien.php',
            method: 'POST',
            data: {
                hoten: hoten,
                to: to
            },
            success: function (res) {
                $('#bang_nv').html(res);
            },

        });

    });



    //tổ
    $('#form-them-moi-to').submit(function (e) {
        e.preventDefault();

        // Nếu hợp lệ thì gửi
        $.post("model/addTo.php", $(this).serialize(), function (res) {
            $("#modalThemMoi").modal("hide");
            $("#form-them-moi-to")[0].reset(); // Reset form

            $("#bang_to").prepend(res); // Thêm lên đầu bảng
            showToast('Thêm mới thành công', 'success');
        });
    });


    // Khi bấm xác nhận xóa
    $('#btnXacNhanXoaTo').on('click', function () {
        if (!idCanXoa) return;

        $.ajax({
            url: 'model/deleteTo.php',
            method: 'POST',
            data: { id: idCanXoa },
            success: function (response) {
                if (response == 'success') {
                    $('tr[data-id="' + idCanXoa + '"]').remove();
                    $('#modalXacNhanXoa').modal('hide');
                    showToast('Xóa thành công', 'success');
                } else {
                    alert('Xóa thất bại. Vui lòng thử lại!');
                }
            },
            error: function () {
                alert('Có lỗi xảy ra khi gửi yêu cầu xóa.');
            }
        });
        $('#modalXacNhanXoa').modal('hide');
    });

    $(document).on('click', '.btn_edit_to', function (e) {
        let id = $(this).data('id');

        $.ajax({
            url: 'model/editTo.php',
            method: 'POST',
            data: { id: id },
            success: function (res) {
                $('#form-chinh-sua-to').html(res);
                $('#modalChinhSua').modal('show');
            },

        });

    });

    $('#form-chinh-sua-to').submit(function (e) {
        e.preventDefault();
        let data = $(this).serialize();

        let id_to = $('#edit_id_to').val();           // ID nhân viên
        let tento = $('#edit_tento').val();
        $.ajax({
            url: 'model/updateTo.php',
            method: 'POST',
            data: data,
            success: function (res) {
                if (res.trim() === "success") {
                    // 1. Đóng modal
                    $('#modalChinhSua').modal('hide');

                    // 2. Hiển thị thông báo
                    showToast('Cập nhật thành công', 'success');

                    // 3. Cập nhật dòng tương ứng trong bảng
                    let row = $(`tr[data-id='${id_to}']`);
                    row.find('td:eq(0)').text(tento);     // Cột họ tên
                } else {
                    showToast('Cập nhật thấts bại', 'error');
                }

            },
        })
    });


    //chỉnh sửa phiếu xuất
    $(document).on("click", ".xoa-vattu-xuat", function () {
        let px = $(this).data('idpx');
        let vattu = $(this).data('vattu');
        const row = $(this).closest("tr");
        const id = row.data("id");
        $.ajax({
            url: 'model/xoaVattuPhieuXuat.php',
            method: 'POST',
            data: { px: px, vattu: vattu },
            success: function (res) {
                if (res.trim() == "success") {
                    row.remove();
                    showToast('Đã xóa vật tư khỏi danh sách', 'success');
                }
            },

        });

    });


    $(document).on("click", ".xoa-vattu-ung", function () {
        let pu = $(this).data('idpu');
        let vattu = $(this).data('vattu');
        const row = $(this).closest("tr");
        const id = row.data("id");
        $.ajax({
            url: 'model/xoaVattuPhieuUng.php',
            method: 'POST',
            data: { pu: pu, vattu: vattu },
            success: function (res) {
                if (res.trim() == "success") {
                    row.remove();
                    showToast('Đã xóa vật tư khỏi danh sách', 'success');
                }
            },

        });

    });

    $(document).on("click", ".xoa-vattu-nhap", function () {
        let pn = $(this).data('idpn');
        let vattu = $(this).data('vattu');
        const row = $(this).closest("tr");
        const id = row.data("id");
        $.ajax({
            url: 'model/xoaVattuPhieuNhap.php',
            method: 'POST',
            data: { pn: pn, vattu: vattu },
            success: function (res) {
                if (res.trim() == "success") {
                    row.remove();
                    showToast('Đã xóa vật tư khỏi danh sách', 'success');
                }
            },

        });

    });


    $("#form-sua-phieu-xuat").submit(function (e) {
        e.preventDefault(); // Ngăn gửi form mặc định

        let hopLe = true;

        // Kiểm tra số phiếu
        const soPhieu = $('input[name="so_phieu"]');
        if (!soPhieu.val().trim()) {
            hopLe = false;
            soPhieu.focus();
            return;
        }

        // Kiểm tra tổ
        const to = $('select[name="to"]');
        if (to.val() == "0") {
            hopLe = false;
            to.focus();
            return;
        }

        // Kiểm tra người đề nghị
        const nguoi = $('select[name="nguoi_de_nghi"]');
        if (nguoi.val() == "0") {
            hopLe = false;
            nguoi.focus();
            return;
        }

        // Kiểm tra có chọn vật tư chưa
        const rows = $("#ds-vattu-da-chon tr");
        if (rows.length == 0) {
            $('#tim-vattu').focus(); // quay lại ô tìm vật tư
            return;
        }

        // Kiểm tra số lượng từng vật tư
        let hasError = false;
        rows.each(function () {
            const slKho = parseFloat($(this).find(".sl-kho").val());
            const slXuatInput = $(this).find(".sl-xuat");
            const slXuat = parseFloat(slXuatInput.val());

            if (!slXuat || slXuat <= 0 || slXuat > slKho) {
                slXuatInput.focus();
                hasError = true;
                return false;
            }
        });

        if (hasError) return;

        // Nếu hợp lệ thì gửi
        $.post("model/suaPhieuXuat.php", $(this).serialize(), function (res) {
            if (res.trim() === "success") {
                // Nếu cần thông báo trước khi reload
                showToast("Cập nhật thành công", "success");
                setTimeout(() => {
                    location.reload();
                }, 1000); // đợi 0.5s cho toast hiển thị
            } else {
                showToast("Có lỗi xảy ra khi cập nhật", "error");
            }

        });
    });


    $("#form-sua-phieu-ung").submit(function (e) {
        e.preventDefault(); // Ngăn gửi form mặc định

        let hopLe = true;



        // Kiểm tra tổ
        const to = $('select[name="to"]');
        if (to.val() == "0") {
            hopLe = false;
            to.focus();
            return;
        }

        // Kiểm tra người đề nghị
        const nguoi = $('select[name="nguoi_de_nghi"]');
        if (nguoi.val() == "0") {
            hopLe = false;
            nguoi.focus();
            return;
        }

        // Kiểm tra có chọn vật tư chưa
        const rows = $("#ds-vattu-da-chon tr");
        if (rows.length == 0) {
            $('#tim-vattu').focus(); // quay lại ô tìm vật tư
            return;
        }

        // Kiểm tra số lượng từng vật tư
        let hasError = false;
        rows.each(function () {
            const slKho = parseFloat($(this).find(".sl-kho").val());
            const slXuatInput = $(this).find(".sl-xuat");
            const slXuat = parseFloat(slXuatInput.val());

            if (!slXuat || slXuat <= 0 || slXuat > slKho) {
                slXuatInput.focus();
                hasError = true;
                return false;
            }
        });

        if (hasError) return;

        // Nếu hợp lệ thì gửi
        $.post("model/suaPhieuUng.php", $(this).serialize(), function (res) {
            if (res.trim() === "success") {
                // Nếu cần thông báo trước khi reload
                showToast("Cập nhật thành công", "success");
                setTimeout(() => {
                    location.reload();
                }, 1000); // đợi 0.5s cho toast hiển thị
            } else {
                showToast("Có lỗi xảy ra khi cập nhật", "error");
            }

        });
    });

    $("#form-sua-phieu-nhap").submit(function (e) {
        e.preventDefault(); // Ngăn gửi form mặc định

        let hopLe = true;

        const ngaynhap = $('input[name="ngaynhap"]');
        if (ngaynhap.val() == "0") {
            hopLe = false;
            ngaynhap.focus();
            return;
        }

        // Kiểm tra tổ
        const nhacungcap = $('input[name="nhacungcap"]');
        if (nhacungcap.val() == "0") {
            hopLe = false;
            nhacungcap.focus();
            return;
        }

        // Kiểm tra có chọn vật tư chưa
        const rows = $("#ds-vattu-da-chon tr");
        if (rows.length == 0) {
            $('#tim-vattu').focus(); // quay lại ô tìm vật tư
            return;
        }

        // // Kiểm tra số lượng từng vật tư
        // let hasError = false;
        // rows.each(function () {
        //     const slKho = parseFloat($(this).find(".sl-kho").val());
        //     const slXuatInput = $(this).find(".sl-xuat");
        //     const slXuat = parseFloat(slXuatInput.val());

        //     if (!slXuat || slXuat <= 0 || slXuat > slKho) {
        //         slXuatInput.focus();
        //         hasError = true;
        //         return false;
        //     }
        // });

        // if (hasError) return;

        // Nếu hợp lệ thì gửi
        $.post("model/suaPhieuNhap.php", $(this).serialize(), function (res) {
            if (res.trim() === "success") {
                // Nếu cần thông báo trước khi reload
                showToast("Cập nhật thành công", "success");
                setTimeout(() => {
                    location.reload();
                }, 1000); // đợi 0.5s cho toast hiển thị
            } else {
                showToast("Có lỗi xảy ra khi cập nhật", "error");
            }

        });
    });

    $('#thongke_to').change(function () {
        let to = $('#thongke_to').val();
        let tenvt = $('#thongke_vattu').val();
        $.ajax({
            url: 'model/thongke.php',
            method: 'POST',
            data: { to: to, tenvt: tenvt },
            success: function (res) {
                $('#tbody_thongke').html(res);

            },

        });
    })



    $('#thongke_vattu').keyup(function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            let to = $('#thongke_to').val();
            let tenvt = $('#thongke_vattu').val();
            $.ajax({
                url: 'model/thongke.php',
                method: 'POST',
                data: { to: to, tenvt: tenvt },
                success: function (res) {
                    $('#tbody_thongke').html(res);

                },

            });
        }, 350);

    })


    // Tổng hợp
    $('#search_tonghop_kho').change(function () {
        let tenkho = $('#search_tonghop_kho').val();
        let tenvt = $('#search_tonghop_ten').val();
        let date_start = $('#date_start_tonghop').val();
        let date_end = $('#date_end_tonghop').val();
        $.ajax({
            url: 'model/tonghop.php',
            method: 'POST',
            data: { tenkho: tenkho, tenvt: tenvt, date_end: date_end, date_start: date_start },
            success: function (res) {
                $('#bang_tonghop').html(res);

            },

        });
    });


    $('#search_tonghop_ten').keyup(function () {
        let tenkho = $('#search_tonghop_kho').val();
        let tenvt = $('#search_tonghop_ten').val();
        let date_start = $('#date_start_tonghop').val();
        let date_end = $('#date_end_tonghop').val();
        $.ajax({
            url: 'model/tonghop.php',
            method: 'POST',
            data: { tenkho: tenkho, tenvt: tenvt, date_end: date_end, date_start: date_start },
            success: function (res) {
                $('#bang_tonghop').html(res);

            },

        });
    })

    $('#date_start_tonghop').change(function () {
        let tenkho = $('#search_tonghop_kho').val();
        let tenvt = $('#search_tonghop_ten').val();
        let date_start = $('#date_start_tonghop').val();
        let date_end = $('#date_end_tonghop').val();
        $.ajax({
            url: 'model/tonghop.php',
            method: 'POST',
            data: { tenkho: tenkho, tenvt: tenvt, date_end: date_end, date_start: date_start },
            success: function (res) {
                $('#bang_tonghop').html(res);

            },

        });
    });

    $('#date_end_tonghop').change(function () {
        let tenkho = $('#search_tonghop_kho').val();
        let tenvt = $('#search_tonghop_ten').val();
        let date_start = $('#date_start_tonghop').val();
        let date_end = $('#date_end_tonghop').val();
        $.ajax({
            url: 'model/tonghop.php',
            method: 'POST',
            data: { tenkho: tenkho, tenvt: tenvt, date_end: date_end, date_start: date_start },
            success: function (res) {
                $('#bang_tonghop').html(res);

            },

        });
    })
    $('#btn_excel').click(function () {

        let tenkho = $('#search_tonghop_kho').val();
        let tenvt = $('#search_tonghop_ten').val();
        let date_start = $('#date_start_tonghop').val();
        let date_end = $('#date_end_tonghop').val();

        $.ajax({
            url: 'model/tonghop.php',
            method: 'POST',
            data: {
                tenkho: tenkho,
                tenvt: tenvt,
                date_start: date_start,
                date_end: date_end,
                mode: 'excel'
            },
            xhrFields: {
                responseType: 'blob' // 👈 QUAN TRỌNG
            },
            success: function (data) {

                let blob = new Blob([data], { type: 'application/vnd.ms-excel' });

                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'baocao_kho.xls';

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });

    });



});
