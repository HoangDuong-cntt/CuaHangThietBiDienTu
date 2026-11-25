# Cửa Hàng Thiết Bị Điện Tử (Electronic Store)

Hệ thống website thương mại điện tử chuyên kinh doanh thiết bị điện tử, công nghệ, linh phụ kiện được xây dựng trên nền tảng **Laravel 13** và **PHP 8.5**, triển khai toàn diện trên môi trường **Docker**.

---

## 📖 Hướng dẫn Setup & Vận hành

👉 **Xem chi tiết tài liệu hướng dẫn đầy đủ tại:** [SETUP_GUIDE.md](SETUP_GUIDE.md)

### Tóm tắt khởi động nhanh (Quick Start):

1. **Bật toàn bộ dịch vụ Docker:**
   ```powershell
   docker compose up -d
   # hoặc: .\sail.ps1 up -d
   ```

2. **Truy cập ứng dụng:**
   - **Website:** [http://localhost:8000](http://localhost:8000)
   - **phpMyAdmin:** [http://localhost:8080](http://localhost:8080) (User: `sail` | Password: `password`)
   - **Mailpit:** [http://localhost:8025](http://localhost:8025)

3. **Chạy Migration & Build Frontend:**
   ```powershell
   docker compose exec laravel.test php artisan migrate
   docker compose exec laravel.test npm run build
   ```

4. **Tắt ứng dụng:**
   ```powershell
   docker compose down
   # hoặc: .\sail.ps1 down
   ```
