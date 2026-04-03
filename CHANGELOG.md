# Changelog — ACF Units Field

## 1.1.0 — 2026-03-31

### Added
- "Kullanılacak Birimler" select2 (multiple, sortable) — boş bırakılırsa tüm birimler aktif
- "Varsayılan Birim" select — seçili birimlere göre dinamik güncellenir, boşsa tümü listelenir
- "Tümünü Seç / Temizle" link'leri
- `get_all_units()` static API method — diğer plugin'ler (breakpoints vb.) birim listesini buradan çeker
- Yeni CSS birimleri: dvw, dvh, svw, svh, lvw, lvh, cqw, cqh, cap, ic, lh, rlh
- Dosya başında detaylı CSS birim referans tablosu (her birimin px karşılığı ve açıklaması)

### Changed
- Birim listesi kategorilere göre gruplandı (absolute, relative-font, viewport, container query, grid, special)
- `render_field` artık `allowed_units` ve `default_unit` field ayarlarını kullanıyor

### Fixed
- Geriye uyumluluk: `allowed_units` boşsa tüm birimler aktif (eski field'lar bozulmaz)

## 1.0.5

- Önceki stabil versiyon
