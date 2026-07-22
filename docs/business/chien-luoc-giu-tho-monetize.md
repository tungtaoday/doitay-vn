# Chiến lược tổng thể doitay — Giữ thợ & Monetize

> Tổng hợp mạch trao đổi chiến lược (2026-07). Luận điểm cốt lõi của chủ dự án:
> **Thợ là tài sản. Marketplace kết nối khách–thợ chỉ là cỗ máy THU HÚT và GIỮ CHÂN
> thợ. Tiền thật đến từ MONETIZE base thợ — doitay như một thương hiệu, và như một
> kênh bán hàng hoá/dịch vụ cho thợ.**
>
> Tài liệu liên quan: `sale-service-model.md` (KPI CTV), `van-hanh-nen-tang.md`
> (vận hành), `fm-model-doitay.xlsx` (tài chính, doanh thu = 0 giai đoạn bootstrap).

---

## 1. Luận điểm cốt lõi (the thesis)

```
Marketplace (kết nối khách ↔ thợ)  =  MỒI  →  hút & giữ thợ
Base thợ gắn bó, tin tưởng, cao tần =  TÀI SẢN
Monetize                            =  (a) doitay là THƯƠNG HIỆU uy tín thợ muốn gắn tên
                                       (b) KÊNH BÁN cho thợ: vật tư, bảo hiểm, tài chính,
                                           đào tạo, đồng phục...
```

Không kiếm tiền từ phía khách (khó — xem §2). Dùng nhu cầu của khách làm **lưỡi câu**
để gom + giữ thợ, rồi kiếm tiền ở **phía thợ** — phía cao tần, gắn bó, sẵn chi trả.

Đây là mô hình "bán cho phía cung": tập hợp lực lượng thợ rồi phục vụ chính họ. ĐKKD
của Hộ KD (ngành **phần mềm + bán hàng hoá**) đã khớp sẵn hướng này.

---

## 2. Vì sao — bản chất TẦN SUẤT THẤP

Home services là marketplace **tần suất thấp**: một hộ cần thợ điện/nước/điều hoà chỉ
~1-2 lần/năm mỗi nghề. Hệ quả bắt buộc phải nhìn thẳng:

- **KHÁCH = thấp tần, giao dịch một lần rồi quên.** Không xây được trên "thói quen quay
  lại". Không đốt ads để tạo habit (LTV/khách quá thấp, không hoàn vốn).
- **THỢ = cao tần, muốn việc mỗi ngày/tuần.** Đây là phía **giữ chân được, tạo thói
  quen, có network effect, và CHỊU TRẢ TIỀN.**

→ Bất đối xứng tần suất này là lý do chiến lược đảo về phía thợ. doitay thực chất là
**"nền tảng cho THỢ"**, không phải "app cho khách".

| | Tần suất | Vai trò | Kiếm tiền? |
|---|---|---|---|
| Khách | Thấp (vài lần/năm) | Dòng cầu — thắng ở đúng khoảnh khắc cần | ❌ (mồi câu) |
| **Thợ** | **Cao (hàng tuần)** | **Tài sản — giữ chân & phục vụ** | ✅ (nguồn thu) |

---

## 3. Kiến trúc 3 mặt

```
KHÁCH ──► Web doitay.vn        = nơi TÌM & ĐẶT thợ (discovery, giao dịch một lần)
THỢ  ──► Zalo Mini App          = NHÀ của thợ: nhận lead, xác nhận, ví, hồ sơ, MUA HÀNG
CTV  ──► Web /sale + /quan-tri  = gieo nguồn cung + vận hành
```

- **Thương hiệu:** khách/web dùng **doitay**; pháp nhân & Zalo (Mini App + OA) dùng
  **Ground Truth** (khớp Hộ KD, qua kiểm duyệt Zalo).
- **Auth thợ = Zalo identity, KHÔNG OTP:** Mini App lấy SĐT đã xác thực từ Zalo → khớp
  tài khoản thợ CTV đã nhập. Lead về qua OA/ZNS → bấm là vào thẳng → chỉ BẤM, không gõ.

---

## 4. Kênh VẬT LÝ (CTV) — gieo & kích hoạt cung

Mắt xích Mini App làm kênh này trọn vẹn (trước đây thợ nhập xong không vào được app →
cung chết). Giờ CTV làm trọn vòng **ngay tại chỗ gặp thợ**:

```
CTV gặp thợ (chợ VLXD, cửa hàng điện nước, công trình, nhóm Zalo thợ)
 ① Nhập hồ sơ /sale/nhap: tên·nghề·khu vực·SĐT + CHỤP 3-5 ảnh việc
 ② ✦ Kích hoạt Mini App tại chỗ: thợ đồng ý chia sẻ SĐT Zalo → vào app + follow OA
 ③ Quản lý duyệt → thợ lên chợ + ví 200k + hoa hồng CTV
```

**Vai trò CTV đổi bản chất:** từ "người nhập liệu" → **"người kích hoạt"**. KPI phải đo
**thợ kích hoạt app + follow OA**, và **thợ xác nhận lịch đầu tiên** (thưởng kích hoạt
20k), không chỉ "số hồ sơ nhập".

**Triển địa lý:** kênh vật lý mang tính địa phương → cày theo **quận/huyện** (xem §6).

---

## 5. Kênh ONLINE — 3 dòng

```
├─ CẦU (khách):      Web doitay.vn — SEO "thợ điện Cầu Giấy", ads local, mạng xã hội
├─ GIỮ CHÂN CUNG:    Zalo Mini App + OA/ZNS — có khách → ZNS báo → bấm → xác nhận
└─ CUNG TỰ SINH:     Mini App — thợ tự đăng ký (Zalo native) + thợ giới thiệu thợ
```

**Bánh đà (flywheel) — mỗi thợ là một điểm KÉO CẦU:**

```
Thợ có khách quen offline → doitay phát THẺ QR hồ sơ cho thợ
 → thợ gửi QR cho khách quen → khách vào doitay.vn, thấy thợ + thấy CẢ NỀN TẢNG
 → lần sau cần nghề KHÁC → khách quay lại đặt thợ khác → hút thêm thợ
```

Cung mang theo khách của chính họ vào nền tảng → khách sinh cầu cho thợ khác → vòng lớn dần.

---

## 6. Atomic network — BÃO HOÀ CỤC BỘ (bản tần suất thấp)

Ô nguyên tử = mạng nhỏ nhất tự sống. Công thức:

```
1 QUẬN  ×  2-3 NGHỀ đau nhất (điện, nước, điều hoà)  ×  đủ thợ để mọi yêu cầu được nhận nhanh
```

Ví dụ: **Cầu Giấy × {điện, nước, điều hoà} × ~15-20 thợ sống/nghề**. KHÔNG rải "20 nghề ×
cả thành phố × vài thợ" (= chết vì loãng).

**Vì tần suất thấp, atomic KHÔNG phải "vòng lặp thói quen" mà là bão hoà cục bộ:**
một quận mà (a) đủ thợ đáp ứng mọi nhu cầu, (b) **sở hữu ô tìm kiếm địa phương** (search
thợ ở quận đó là ra doitay), (c) đủ khách hài lòng để **truyền miệng** chạm người cần tiếp theo.

**Đo THANH KHOẢN + THỢ, KHÔNG đo "khách quay lại"** (retention khách diễn ra theo NĂM):

| Chỉ số | Ngưỡng khởi điểm (calibrate) |
|---|---|
| % yêu cầu được 1 thợ nhận ≤60 phút (giờ HC) | ≥ 75% |
| % yêu cầu → thành lịch (matched→confirmed) | ≥ 50% |
| Thợ có việc đều (≥1 lịch/tuần) | phần lớn thợ active |
| **% cầu đến từ organic/referral (không phải ads)** | càng cao càng "tự chạy" |

**5 bước xây:** ① chọn ô → ② gieo CUNG trước (dồn CTV 1 quận) → ③ bơm cầu nhỏ đúng quận,
kiểm chứng thanh khoản → ④ đạt tự chạy (giảm trợ giá vẫn chạy) → ⑤ nhân bản sang quận kề /
thêm nghề. **Không mở ô mới khi ô hiện tại chưa sống.**

**4 nguyên tắc vàng:** (1) gieo phía khó trước = thợ; (2) tập trung, không rải; (3) đo
thanh khoản không đo vanity; (4) trợ giá là mồi lửa không phải nhiên liệu (tắt được vẫn cháy).

---

## 7. GIỮ THỢ — cơ chế retention (trái tim mô hình)

Thợ ở lại vì 5 lớp, tăng dần chi phí rời bỏ:

1. **Việc (income):** có lead đều → lý do cơ bản nhất. Đây là thứ marketplace tạo ra.
2. **Danh tính & thương hiệu:** hồ sơ "doitay verified" = uy tín, tăng thu nhập → thợ
   MUỐN gắn tên. Huy hiệu xác thực trở thành thứ có giá.
3. **Công cụ chạy nghề:** ví, quản lý lịch, thẻ QR, hồ sơ — càng dùng càng dính (switching cost).
4. **Thói quen Zalo:** Mini App = mở hàng ngày → luôn top-of-mind.
5. **Cộng đồng & ghi nhận:** thuộc về một mạng lưới thợ.

→ Thợ đã ở lại + gắn bó + mở app hàng ngày = **điều kiện đủ để bật lớp thương mại (§8).**

---

## 8. MONETIZE — lộ trình 3 giai đoạn

**Giai đoạn 1 — BOOTSTRAP (hiện tại): doanh thu = 0.**
Miễn phí, tặng ví 200k, hoa hồng CTV. Mục tiêu duy nhất: **tạo base thợ sống** trong 1-2
ô nguyên tử. Chi phí tiền mặt = hoa hồng CTV + ZNS + hosting (xem `fm-model`).

**Giai đoạn 2 — THƯƠNG HIỆU + PHÍ LEAD.**
- **Phí lead** (đã code sẵn — `marketplace.lead_fee`): thợ trả để nhận thông tin khách.
  Bật khi chợ đủ thanh khoản (thợ thấy lead đáng tiền). Đây là doanh thu "tự nhiên" đầu tiên.
- **doitay như thương hiệu uy tín:** "thợ doitay verified" thành chuẩn tin cậy khách tìm →
  thợ trả phí duy trì hạng/huy hiệu, gói hiển thị ưu tiên.

**Giai đoạn 3 — KÊNH BÁN CHO THỢ (nguồn thu chính, khớp ĐKKD "bán hàng hoá").**
Khi có base thợ đông + gắn bó + mở Mini App hàng ngày → biến Mini App thành **cửa hàng
cho thợ**. Thợ mua thường xuyên, hiện mua lẻ rải rác — doitay gom lại, giá tốt hơn nhờ
quy mô. Các dòng hàng/dịch vụ:

| Nhóm | Ví dụ | Vì sao thợ mua |
|---|---|---|
| **Vật tư / công cụ** | dây điện, ống nước, gas, phụ kiện, đồ nghề | mua liên tục, đang mua lẻ giá cao |
| **Tài chính** | đồ nghề trả góp, ứng vốn nhỏ | thiếu vốn xoay |
| **Bảo hiểm** | tai nạn lao động, trách nhiệm nghề | rủi ro nghề cao |
| **Đào tạo / chứng chỉ** | kỹ năng, an toàn điện, chứng chỉ nghề | nâng thu nhập, có "verified" |
| **Đồng phục / branding** | áo, name card, standee "doitay verified" | uy tín trước khách |

→ Marketplace **không cần lãi** — nó là chi phí thu hút + giữ thợ. Lãi đến từ **thương
mại trên base thợ**. (Mô hình "bán cuốc xẻng cho người đào vàng".)

---

## 9. Chỉ số đo tổng thể (không đo "khách quay lại")

- **Thanh khoản:** match rate, time-to-match, % yêu cầu → thành lịch (`/quan-tri`).
- **Sức khoẻ cung:** thợ kích hoạt, thợ có việc/tuần, thợ churn.
- **Chất lượng nguồn cầu:** % organic/referral vs ads (càng organic càng khoẻ).
- **Độ phủ discovery:** thứ hạng SEO nghề × quận.
- **(Giai đoạn 3) Thương mại:** ARPU thợ, tỉ lệ thợ mua hàng, doanh thu/thợ/tháng.

---

## 10. Rủi ro & phản biện

- **Home services tần suất thấp là ngành KHÓ** (Thumbtack, HomeAdvisor chật vật): CAC khách
  cao, khách rò rỉ về Google. → Chống: **CAC khách ≈ 0** qua SEO + thợ-tự-kéo-khách, KHÔNG
  phụ thuộc ads; kiếm tiền phía thợ.
- **Bán hàng cho thợ cần niềm tin + quy mô:** chỉ bật Giai đoạn 3 khi base thợ đủ đông &
  gắn bó (đã mở Mini App hàng ngày). Bán sớm khi thợ chưa tin = phản tác dụng.
- **Phụ thuộc Zalo:** Mini App/OA là hạ tầng bên thứ ba (rủi ro chính sách). → Giữ dữ liệu
  thợ (SĐT, hồ sơ) ở hệ thống của mình, Zalo chỉ là kênh tiếp cận.
- **Nút thắt hiện tại:** đưa Mini App qua duyệt Zalo (danh mục Công nghệ/Phần mềm theo ĐKKD,
  không phải "Sửa chữa"). Chưa qua cửa này thì cả chuỗi giữ-thợ chưa khởi động được.

---

## Tóm tắt một câu

**doitay dùng nhu cầu tìm thợ của khách (thấp tần) làm mồi để gom và giữ lực lượng thợ
(cao tần), rồi kiếm tiền bằng cách phục vụ chính base thợ đó — như một thương hiệu uy tín
và một kênh bán vật tư/dịch vụ cho thợ.**
