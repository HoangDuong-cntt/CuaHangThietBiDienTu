# 🗺️ LỘ TRÌNH THỰC HIỆN CHI TIẾT DÀNH CHO THÀNH VIÊN 1
## DỰ ÁN: HỆ THỐNG THƯƠNG MẠI ĐIỆN TỬ THIẾT BỊ CÔNG NGHỆ THÔNG MINH (SMARTTECH E-COMMERCE)

> **Mục tiêu tài liệu:** Bản hướng dẫn từng bước (Step-by-Step Roadmap) quy định rõ: **Làm cái gì? Làm như thế nào? Thứ tự từng bước ra sao? Để làm gì (Mục đích nghiệp vụ)? Cần bàn giao gì cho các thành viên khác?**  
> Dựa trên tài liệu phân chia công việc trong Đề cương thực tập (50% Backend - 50% Frontend).

---

## 📌 I. TỔNG QUAN PHẠM VI CÔNG VIỆC CỦA THÀNH VIÊN 1

| Hạng mục | Chi tiết phụ trách |
| :--- | :--- |
| **Phân hệ chính (Domain)** | **Xác thực đa kênh, Phân quyền động (RBAC), Sổ địa chỉ, Voucher & Đánh giá (Review), Báo cáo Dashboard** |
| **Tỷ lệ khối lượng** | **50% Backend – 50% Frontend** |
| **Các bảng CSDL sở hữu** | `users`, `password_reset_tokens`, `user_addresses`, `roles`, `permissions`, `role_has_permissions`, `coupons`, `coupon_usages`, `reviews`, `banners` |
| **Các bảng phối hợp đọc dữ liệu** | `orders`, `order_items`, `products` (dùng để truy vấn thống kê Dashboard, xuất file Excel, và ràng buộc đánh giá Verified Purchase) |
| **Vai trò đối với nhóm** | Cung cấp **Middleware xác thực/phân quyền** cho các thành viên khác bọc Route; Cung cấp **API kiểm tra Voucher** cho Thành viên 4 (Giỏ hàng); Cung cấp **Component Đánh giá** cho Thành viên 2 (PDP). |

---

## 🎯 II. DANH SÁCH TOÀN BỘ ĐẦU VIỆC (CHECKLIST TỔNG HỢP)

```
[ ] BƯỚC 1: Xây dựng CSDL & Eloquent Models nền tảng
[ ] BƯỚC 2: Hệ thống Xác thực nâng cao (Auth), Email Verification & Google OAuth2
[ ] BƯỚC 3: Quản lý Hồ sơ cá nhân (Profile) & Sổ địa chỉ nhận hàng đa năng
[ ] BƯỚC 4: Phân quyền truy cập động (Dynamic RBAC) & Middleware bảo vệ hệ thống
[ ] BƯỚC 5: Động cơ Khuyến mãi (Voucher / Coupon Engine) & API tính tiền cho Giỏ hàng
[ ] BƯỚC 6: Phân hệ Đánh giá sản phẩm (Product Review & Rating - Verified Purchase)
[ ] BƯỚC 7: Quản trị Banner quảng cáo & Tiếp thị trang chủ
[ ] BƯỚC 8: Admin Dashboard (Thống kê số liệu, Biểu đồ Chart.js) & Xuất báo cáo Excel
[ ] BƯỚC 9: Đóng gói Component bàn giao & Phối hợp tích hợp luồng toàn hệ thống
```

---

## 🚀 III. LỘ TRÌNH THỰC HIỆN TỪNG BƯỚC (STEP-BY-STEP)

---

### 🟢 GIAI ĐOẠN 1: NỀN TẢNG CƠ SỞ DỮ LIỆU & ELOQUENT MODELS

#### 👉 Bước 1: Tạo Migrations và Eloquent Models cho các bảng phụ trách
* **Làm cái gì:**
  1. Tạo các file Migration (hoặc khớp chuẩn theo file `CSDD_THUCTAP_CHINH.sql`):
     - `users`: Bổ sung các cột `phone`, `role` (enum: 'customer', 'staff', 'admin'), `avatar`, `google_id`, `is_active`.
     - `user_addresses`: Quản lý nhiều địa chỉ (`user_id`, `receiver_name`, `receiver_phone`, `province`, `district`, `ward`, `street_address`, `is_default`).
     - `roles`, `permissions`, `role_has_permissions` (nếu làm RBAC động) hoặc quản lý vai trò linh hoạt.
     - `coupons`: Quản lý mã giảm giá (`code`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount_amount`, `usage_limit`, `usage_limit_per_user`, `used_count`, `starts_at`, `expires_at`, `is_active`).
     - `coupon_usages`: Lưu lịch sử khách hàng đã dùng voucher nào (`coupon_id`, `user_id`, `order_id`, `used_at`).
     - `reviews`: Quản lý đánh giá (`user_id`, `product_id`, `order_item_id`, `rating` từ 1-5, `comment`, `admin_reply`, `status` ['pending', 'approved', 'hidden']).
     - `banners`: Quản lý banner (`title`, `image_url`, `link_url`, `sort_order`, `is_active`).
  2. Tạo các Eloquent Model tương ứng trong thư mục `app/Models/`:
     - `User.php`, `UserAddress.php`, `Role.php`, `Permission.php`, `Coupon.php`, `CouponUsage.php`, `Review.php`, `Banner.php`.
  3. Định nghĩa các mối quan hệ (Relationships) chuẩn mực:
     - `User hasMany UserAddress`
     - `User hasMany Review`
     - `User hasMany CouponUsage`
     - `Coupon hasMany CouponUsage`
     - `Review belongsTo User`, `Review belongsTo Product`, `Review belongsTo OrderItem`
  4. Tạo Seeder dữ liệu mẫu (`UserSeeder`, `CouponSeeder`, `BannerSeeder`) để có sẵn tài khoản Admin, Staff, Customer và vài voucher test.
* **Để làm gì:**
  - Thiết lập nền móng dữ liệu vững chắc cho toàn bộ chức năng của mình.
  - Cung cấp sẵn tài khoản mẫu cho cả 4 thành viên trong nhóm đăng nhập kiểm thử ngay mà không bị thiếu dữ liệu.

---

### 🟢 GIAI ĐOẠN 2: PHÂN HỆ XÁC THỰC (AUTHENTICATION) & HỒ SƠ NGƯỜI DÙNG

#### 👉 Bước 2: Xây dựng Đăng ký, Đăng nhập, Quên mật khẩu & Đăng nhập Google
* **Làm cái gì:**
  1. **Backend:**
     - Tạo `AuthController`:
       - `showLoginForm()`, `login()`: Kiểm tra email, password (dùng `Hash::check()`), xử lý "Ghi nhớ đăng nhập" (*Remember Me*), kiểm tra tài khoản có bị khóa (`is_active = 0`) hay không.
       - `showRegisterForm()`, `register()`: Validate dữ liệu qua FormRequest, tạo mới User với mật khẩu mã hóa `bcrypt()`, tự động gán role `customer`.
       - `logout()`: Hủy session, vô hiệu hóa token và chuyển hướng an toàn.
     - **Email Verification (Xác thực email):**
       - Sử dụng tính năng `MustVerifyEmail` có sẵn của Laravel để gửi link xác thực kích hoạt tài khoản qua email khi người dùng đăng ký mới.
     - **Quên & Đặt lại mật khẩu (Password Reset):**
       - Tạo `ForgotPasswordController` & `ResetPasswordController`: Gửi link kèm mã Token an toàn vào email người dùng, cho phép đặt lại mật khẩu mới.
     - **Tích hợp Đăng nhập Google OAuth2 (Điểm nhấn Backend):**
       - Cài đặt thư viện `laravel/socialite`.
       - Cấu hình Google Client ID & Secret trong file `.env` và `config/services.php`.
       - Viết 2 method: `redirectToGoogle()` và `handleGoogleCallback()`: Nếu email Google chưa có trong DB thì tự động tạo tài khoản mới; nếu đã có thì tiến hành đăng nhập ngay.
  2. **Frontend:**
     - Thiết kế giao diện trang Đăng nhập (`resources/views/auth/login.blade.php`): Form nhập Email, Password, nút tick "Ghi nhớ", nút bấm nổi bật **"Đăng nhập với Google"** (kèm logo Google), link Quên mật khẩu.
     - Thiết kế giao diện trang Đăng ký (`resources/views/auth/register.blade.php`): Form nhập Họ tên, Email, Số điện thoại, Mật khẩu, Xác nhận mật khẩu, hiển thị thông báo lỗi Validation trực quan.
     - Thiết kế trang Quên mật khẩu & Đặt lại mật khẩu.
     - Cập nhật **Header / Navbar chung của website**: Hiển thị trạng thái khi chưa đăng nhập (Nút Đăng nhập/Đăng ký) và khi đã đăng nhập (Hiển thị Avatar, Tên người dùng và Dropdown Menu: *Tài khoản cá nhân, Đơn hàng của tôi, Đăng xuất*).
* **Để làm gì:**
  - Bảo vệ an toàn danh tính người dùng.
  - Đăng nhập bằng Google giúp người dùng mua hàng nhanh chóng chỉ với 1 click, không tốn thời gian gõ mật khẩu.
  - Cung cấp cơ chế phiên làm việc (Session Auth) chuẩn để Thành viên 4 liên kết đơn hàng với người mua.

---

#### 👉 Bước 3: Quản lý Hồ sơ cá nhân (Profile) & Sổ địa chỉ nhận hàng
* **Làm cái gì:**
  1. **Backend:**
     - Tạo `ProfileController`:
       - `edit()`, `updateProfile()`: Cho phép khách hàng cập nhật họ tên, số điện thoại, tải lên ảnh đại diện (*Avatar*).
       - `updatePassword()`: Đổi mật khẩu (bắt buộc kiểm tra mật khẩu hiện tại chính xác trước khi cho đổi).
     - Tạo `AddressController`:
       - `index()`: Lấy danh sách địa chỉ của user đang đăng nhập.
       - `store()`: Thêm mới địa chỉ nhận hàng. Nếu user chọn làm mặc định (`is_default = 1`), tự động cập nhật các địa chỉ khác về `is_default = 0`.
       - `update()`, `destroy()`: Sửa thông tin địa chỉ hoặc xóa địa chỉ.
       - `setDefault($id)`: Đặt nhanh 1 địa chỉ làm mặc định.
       - **API Endpoint:** `GET /api/user/addresses` trả về danh sách địa chỉ định dạng JSON để Thành viên 4 gọi chọn địa chỉ khi sang trang Thanh toán (Checkout).
  2. **Frontend:**
     - Giao diện Trang quản lý tài khoản (`resources/views/customer/profile.blade.php`):
       - Tab 1: Cập nhật thông tin cá nhân & Đổi mật khẩu.
       - Tab 2: Quản lý Sổ địa chỉ: Danh sách thẻ địa chỉ, có huy hiệu `[Mặc định]`, các nút bấm Sửa, Xóa, Đặt làm mặc định.
       - Modal popup thêm mới / chỉnh sửa địa chỉ nhanh chóng.
* **Để làm gì:**
  - Giúp khách hàng quản lý nhiều địa chỉ nhận hàng (nhà riêng, công ty).
  - Khi mua hàng, khách chỉ cần click chọn 1 địa chỉ có sẵn từ Sổ địa chỉ, không cần nhập lại từ đầu.

---

### 🟢 GIAI ĐOẠN 3: PHÂN QUYỀN ĐỘNG & BẢO MẬT HỆ THỐNG (RBAC)

#### 👉 Bước 4: Xây dựng Phân quyền truy cập động (Dynamic RBAC) & Middleware
* **Làm cái gì:**
  1. **Backend:**
     - Xây dựng cấu trúc phân quyền:
       - Các quyền hạn chi tiết (*Permissions*): `view_dashboard`, `manage_products`, `manage_categories`, `manage_orders`, `manage_coupons`, `manage_reviews`, `manage_users`, `manage_banners`.
       - Bảng ánh xạ gán quyền cho vai trò (*Role-Permissions*).
     - Tạo Middleware kiểm tra phân quyền:
       - `AdminMiddleware`: Chỉ cho phép tài khoản có `role = 'admin'` hoặc `role = 'staff'` truy cập vào các trang `/admin/*`. Chặn toàn bộ `customer` hoặc khách chưa đăng nhập.
       - `CheckPermissionMiddleware`: Kiểm tra tài khoản Staff có quyền cụ thể cho từng module hay không (Ví dụ: Staff kho chỉ vào được trang Quản lý đơn hàng, không vào được trang Cấu hình Voucher hoặc Doanh thu).
     - Tạo `RolePermissionController` (Admin):
       - Hiển thị ma trận vai trò và danh sách quyền hạn.
       - Lưu việc phân bổ quyền hạn cho từng vai trò / nhân viên.
  2. **Frontend:**
     - Giao diện Admin - Ma trận phân quyền (`resources/views/admin/roles/index.blade.php`):
       - Bảng phân quyền trực quan: Cột là các Role (Admin, Nhân viên bán hàng, Nhân viên kho), Hàng là các Quyền hạn theo từng module.
       - Admin chỉ cần tick/untick vào checkbox để cấp hoặc thu hồi quyền hạn của nhân viên.
* **Để làm gì:**
  - Đảm bảo an toàn thông tin doanh nghiệp: Nhân viên chỉ xem và làm đúng nghiệp vụ được giao, không can thiệp vào doanh thu hay xóa dữ liệu quan trọng.
  - **Bàn giao cho nhóm:** Cung cấp Middleware cho Thành viên 2, 3, 4 để các bạn bọc vào nhóm Route quản trị tương ứng trong `routes/web.php`.

---

### 🟢 GIAI ĐOẠN 4: ĐỘNG CƠ KHUYẾN MÃI (COUPON / VOUCHER ENGINE)

#### 👉 Bước 5: Quản lý Voucher & API kiểm tra giảm giá cho Giỏ hàng
* **Làm cái gì:**
  1. **Backend:**
     - Tạo `CouponController` (Admin):
       - CRUD Mã giảm giá: Mã Code (viết hoa, unique), Loại giảm (`percentage` hoặc `fixed_amount`), Mức giảm, Đơn tối thiểu (`min_order_amount`), Giảm tối đa (`max_discount_amount`), Giới hạn tổng số lượt (`usage_limit`), Giới hạn mỗi khách (`usage_limit_per_user`), Thời gian bắt đầu - kết thúc, Bật/Tắt hoạt động.
     - **Xây dựng Coupon Engine & API (Nhiệm vụ then chốt kết nối với Thành viên 4):**
       - Tạo `POST /api/coupons/apply`:
         - Nhận vào: `coupon_code`, `subtotal` (tổng tiền giỏ hàng), `user_id`.
         - Kiểm tra logic 6 điều kiện nghiêm ngặt:
           1. Mã voucher có tồn tại và đang bật (`is_active = 1`) không?
           2. Thời điểm hiện tại có nằm trong khoảng `starts_at` và `expires_at` không?
           3. Đơn hàng có đạt giá trị tối thiểu `subtotal >= min_order_amount` không?
           4. Voucher có còn lượt sử dụng toàn hệ thống không (`used_count < usage_limit`)?
           5. Khách hàng này đã dùng mã này bao nhiêu lần trong bảng `coupon_usages` (chưa vượt quá `usage_limit_per_user`)?
         - Nếu hợp lệ: Tính toán chính xác số tiền được giảm:
           - Nếu là tiền mặt: `discount = discount_value`.
           - Nếu là %: `discount = subtotal * (discount_value / 100)`. Nếu có `max_discount_amount`, so sánh lấy giá trị nhỏ hơn.
         - Trả về JSON:
           ```json
           {
             "success": true,
             "message": "Áp dụng mã giảm giá thành công!",
             "data": {
               "coupon_id": 1,
               "code": "SMARTTECH500",
               "discount_amount": 500000,
               "final_total": 14500000
             }
           }
           ```
         - Nếu thất bại: Trả về `success: false` kèm thông báo lỗi cụ thể (ví dụ: *"Đơn hàng chưa đạt tối thiểu 2.000.000đ"*, *"Mã giảm giá đã hết lượt dùng"*).
  2. **Frontend:**
     - Giao diện Admin - Quản lý Voucher (`resources/views/admin/coupons/index.blade.php`):
       - Bảng danh sách mã: Mã code, loại giảm, số tiền giảm, số lượt đã dùng/tổng lượt, ngày hết hạn, trạng thái (Badge: *Đang chạy, Hết hạn, Hết lượt*).
       - Form thêm mới / chỉnh sửa voucher có kiểm tra điều kiện đầu vào.
* **Để làm gì:**
  - Thúc đẩy doanh số thông qua các chiến dịch kích cầu mua sắm.
  - API chuẩn xác giúp **Thành viên 4** chỉ cần gọi AJAX từ Giỏ hàng / Checkout là tự động trừ tiền mượt mà, không sợ gian lận mã.

---

### 🟢 GIAI ĐOẠN 5: ĐÁNH GIÁ SẢN PHẨM (REVIEW & RATING - VERIFIED PURCHASE)

#### 👉 Bước 6: Phân hệ Đánh giá, Chấm sao & Kiểm duyệt Admin
* **Làm cái gì:**
  1. **Backend:**
     - Tạo `ReviewController` (Storefront & Admin):
       - **Logic Ràng buộc Đã mua hàng (Verified Purchase - Chống đánh giá ảo):**
         - Khi khách hàng gửi yêu cầu đánh giá cho sản phẩm `product_id`, kiểm tra trong bảng `orders` join `order_items`:
           - Có tồn tại đơn hàng của user này mua sản phẩm đó hay không?
           - Trạng thái đơn hàng bắt buộc phải là `completed` (Đã giao hàng thành công).
           - Mỗi dòng đơn hàng (`order_item_id`) chỉ được viết đánh giá 1 lần duy nhất (ràng buộc Unique).
       - **Xử lý lưu đánh giá:**
         - Lưu số sao chấm (1 đến 5 sao), nhận xét chi tiết.
         - Xử lý upload hình ảnh thực tế của khách hàng (lưu vào thư mục `storage/app/public/reviews/`).
         - Tích hợp bộ lọc từ ngữ phản cảm (Bad Words Filter): Tự động đánh dấu hoặc cảnh báo nếu chứa từ ngữ tục tĩu.
         - Mặc định trạng thái đánh giá là `pending` hoặc `approved` tùy theo cấu hình kiểm duyệt.
       - **Thuật toán thống kê Rating của sản phẩm:**
         - Viết method tính: Số sao trung bình (ví dụ: 4.8/5.0) và tỷ lệ % phân bổ của từng mốc sao (5 sao: 80%, 4 sao: 15%, 3 sao: 5%,...).
       - **Admin Quản trị Đánh giá:**
         - Duyệt / Ẩn đánh giá vi phạm (`status = 'hidden'`).
         - Trả lời phản hồi đánh giá của khách (`admin_reply`).
  2. **Frontend:**
     - **Component Khách hàng - Đánh giá sản phẩm (`resources/views/components/product-reviews.blade.php`):**
       - Khối thống kê tổng quan: Điểm sao trung bình lớn, thanh tiến trình tỷ lệ phân bổ sao (5 sao -> 1 sao).
       - Danh sách nhận xét của khách: Avatar, Tên khách, Huy hiệu xanh nổi bật `[✓ Đã mua hàng tại SmartTech]`, số sao chấm, ngày đánh giá, nội dung nhận xét, bộ sưu tập ảnh khách chụp thực tế, câu trả lời từ Cửa hàng.
       - Form gửi đánh giá: Chấm điểm sao tương tác trực quan (bấm chuột đổi màu vàng sao từ 1 đến 5 sao), khung viết nhận xét, nút chọn và xem trước (preview) nhiều ảnh trước khi tải lên.
     - **Giao diện Admin - Kiểm duyệt Đánh giá (`resources/views/admin/reviews/index.blade.php`):**
       - Bảng danh sách review: Khách hàng, Sản phẩm, Số sao, Nội dung nhận xét, Ảnh đính kèm.
       - Nút chuyển trạng thái nhanh (Duyệt / Ẩn).
       - Modal nhập câu trả lời của Shop (`admin_reply`).
* **Để làm gì:**
  - Tăng độ tin cậy và uy tín tối đa cho sản phẩm (khách hàng khác nhìn thấy huy hiệu *Đã mua hàng thật* sẽ an tâm đặt mua).
  - **Bàn giao cho nhóm:** Đóng gói thành Component chuẩn Blade để **Thành viên 2** chỉ việc gọi `@include('components.product-reviews')` vào trang chi tiết sản phẩm (PDP).

---

### 🟢 GIAI ĐOẠN 6: QUẢN LÝ TIẾP THỊ & BANNER TRANG CHỦ

#### 👉 Bước 7: Quản lý Banner & Slider quảng cáo
* **Làm cái gì:**
  1. **Backend:**
     - Tạo `BannerController` (Admin):
       - CRUD Banner quảng cáo: Tiêu đề banner, xử lý upload file ảnh sắc nét, link liên kết khi click vào, thứ tự hiển thị (`sort_order`), công tắc bật/tắt hiển thị (`is_active`).
     - Tạo View Composer hoặc Service cung cấp danh sách Banner đang hoạt động ra trang chủ cho toàn nhóm.
  2. **Frontend:**
     - Giao diện Admin - Quản lý Banner (`resources/views/admin/banners/index.blade.php`):
       - Danh sách banner kèm ảnh xem trước thumbnail.
       - Form tải ảnh banner, nhập thứ tự sắp xếp và bật/tắt.
* **Để làm gì:**
  - Giúp Quản trị viên dễ dàng thay đổi các chương trình khuyến mãi, sự kiện Black Friday, giảm giá tựu trường trực tiếp trên trang chủ mà không cần sửa code.

---

### 🟢 GIAI ĐOẠN 7: ADMIN DASHBOARD & BÁO CÁO THỐNG KÊ DOANH SỐ

#### 👉 Bước 8: Thống kê số liệu, Vẽ biểu đồ Chart.js & Xuất báo cáo Excel
* **Làm cái gì:**
  1. **Backend:**
     - Tạo `DashboardController` (Admin):
       - Viết các câu truy vấn tổng hợp số liệu kinh doanh:
         - Tổng doanh thu theo khoảng thời gian (Hôm nay, Tuần này, Tháng này, Năm nay) từ bảng `orders` (chỉ tính các đơn có `order_status = 'completed'` hoặc `payment_status = 'paid'`).
         - Tổng số đơn hàng mới phát sinh cần duyệt.
         - Tổng số khách hàng mới đăng ký trong tháng.
         - Top 5 sản phẩm bán chạy nhất (dựa trên bảng `order_items` nhóm theo `product_id`).
       - API / Dữ liệu trả về cho Biểu đồ:
         - Doanh thu 12 tháng trong năm hiện tại (mảng 12 tháng).
         - Số lượng đơn hàng theo trạng thái (Biểu đồ tròn: Chờ xử lý, Đang giao, Hoàn thành, Đã hủy).
     - **Xuất Báo cáo Excel (Laravel Excel / Maatwebsite):**
       - Cài đặt thư viện `maatwebsite/excel`.
       - Tạo Export Class: `OrdersExport` và `RevenueReportExport`.
       - Viết method `exportRevenueExcel()`: Lọc theo khoảng ngày (từ ngày... đến ngày...) và tải về file `.xlsx` chuyên nghiệp gồm các cột: Mã đơn, Khách hàng, Ngày đặt, Phương thức thanh toán, Tiền hàng, Giảm giá Voucher, Doanh thu thực nhận.
  2. **Frontend:**
     - Giao diện Admin Dashboard (`resources/views/admin/dashboard.blade.php`):
       - Hàng thẻ Card tóm tắt KPI số liệu tổng quan (Tổng Doanh thu, Đơn hàng mới, Khách hàng mới, Đánh giá mới) kèm icon và tỷ lệ tăng trưởng.
       - Khung Biểu đồ đường (Line Chart) / Cột (Bar Chart) sử dụng thư viện `Chart.js` hiển thị doanh thu theo các tháng.
       - Khung Biểu đồ tròn (Doughnut Chart) biểu diễn cơ cấu trạng thái đơn hàng.
       - Bảng Top 5 sản phẩm bán chạy nhất có hình ảnh và doanh số.
       - Nút bấm *"Xuất báo cáo Excel"* kèm bộ chọn khoảng ngày xuất file.
* **Để làm gì:**
  - Cung cấp bức tranh toàn cảnh về tình hình kinh doanh cho Quản trị viên điều hành.
  - File Excel xuất ra phục vụ công tác kế toán, đối soát tài chính và làm báo cáo minh chứng thực tập cho Giảng viên hướng dẫn.

---

### 🟢 GIAI ĐOẠN 8: KIỂM THỬ, TỐI ƯU & BÀN GIAO CHO NHÓM

#### 👉 Bước 9: Kiểm thử bảo mật, tinh chỉnh giao diện & Tích hợp liên kết
* **Làm cái gì:**
  1. **Kiểm tra an toàn bảo mật:**
     - Kiểm tra toàn bộ các form nhập liệu đều có mã `@csrf`.
     - Kiểm tra phân quyền: Thử lấy tài khoản khách hàng gõ link `/admin/dashboard` hoặc `/admin/coupons` -> đảm bảo bị Middleware chặn lại và redirect ra trang lỗi 403 Forbidden.
  2. **Bàn giao cho các thành viên trong nhóm:**
     - **Bàn giao cho Thành viên 4 (Giỏ hàng & Đơn hàng):**
       - URL API kiểm tra voucher: `POST /api/coupons/apply` kèm danh sách tham số mẫu.
       - API lấy sổ địa chỉ người dùng: `GET /api/user/addresses`.
     - **Bàn giao cho Thành viên 2 (Trang chi tiết sản phẩm - PDP):**
       - Component đánh giá: `@include('components.product-reviews', ['product' => $product])`.
     - **Bàn giao cho Thành viên 2, 3, 4 (Phân quyền Route):**
       - Middleware `auth`, `admin`, `permission` để các bạn bọc route quản trị của mình trong `routes/web.php`.
* **Để làm gì:**
  - Đảm bảo dự án không phát sinh lỗi xung đột khi ghép nối mã nguồn giữa 4 thành viên.
  - Hoàn thiện 100% chỉ tiêu công việc cá nhân của Thành viên 1 theo đúng Đề cương thực tập.

---

## 📅 IV. BẢNG TIẾN ĐỘ THỰC HIỆN THEO TUẦN (GỢI Ý KẾ HOẠCH)

| Thời gian | Trọng tâm công việc | Kết quả bàn giao (Deliverable) |
| :---: | :--- | :--- |
| **Tuần 1** | Chuẩn hóa CSDL các bảng phụ trách, viết Migrations, Models & Seeders mẫu | Các bảng CSDL tạo xong, Seeder chạy mượt |
| **Tuần 2** | Lập trình module Xác thực (Auth), Email Verification, Quên mật khẩu & Đăng nhập Google | Đăng nhập/Đăng ký hoạt động hoàn chỉnh, Header đổi trạng thái |
| **Tuần 3** | Lập trình Profile cá nhân, CRUD Sổ địa chỉ & Module Phân quyền động (RBAC Matrix) | Trang Profile, Sổ địa chỉ và Ma trận phân quyền hoạt động tốt |
| **Tuần 4** | Lập trình Động cơ Voucher (Coupon Engine) & Xây dựng API tính tiền cho Giỏ hàng | CRUD Voucher Admin hoàn thành, API check voucher sẵn sàng |
| **Tuần 5** | Lập trình Đánh giá sản phẩm (Verified Purchase), Chấm sao, Duyệt review & Banner | Component Review hoạt động, Admin duyệt được đánh giá |
| **Tuần 6** | Lập trình Dashboard thống kê, Biểu đồ Chart.js & Xuất file báo cáo Excel | Dashboard trực quan số liệu, xuất Excel thành công |
| **Tuần 7** | Ghép nối API với Thành viên 4, nhúng Component vào PDP của Thành viên 2, kiểm thử bảo mật | Hệ thống kết nối thông suốt, không bị lỗi phân quyền |
| **Tuần 8** | Rà soát lỗi toàn diện, làm tài liệu minh chứng, viết báo cáo cá nhân | Báo cáo thực tập hoàn chỉnh phần của Thành viên 1 |

---

## 💡 V. LỜI KHUYÊN KHI TỰ CODE (DÀNH CHO BẠN)
1. **Làm tuần tự từ CSDL -> Model -> Controller -> View -> Route:** Đừng nhảy cóc làm giao diện trước khi chưa có CSDL và logic xử lý.
2. **Tận dụng Seeder:** Khi các bạn khác chưa làm xong phần Sản phẩm hay Đơn hàng, hãy dùng Seeder tạo sẵn vài đơn hàng giả định (`orders`, `order_items`) trong Database để bạn lập trình Dashboard và kiểm tra tính năng Review ngay mà không cần đợi người khác.
3. **Thống nhất chuẩn API JSON:** Khi viết API kiểm tra Voucher cho Thành viên 4, hãy giữ đúng cấu trúc `{ success: true/false, message: '...', data: { ... } }` để Thành viên 4 gọi AJAX dễ dàng nhất.
