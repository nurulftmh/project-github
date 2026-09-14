from pathlib import Path
import pandas as pd
import numpy as np

# ============================================================
# KONFIGURASI
# Letakkan script ini di folder yang sama dengan file Excel.
# ============================================================
INPUT_FILE = Path("simulationdataanalyst 2(1).xlsx")
OUTPUT_FILE = Path("hasil_analisis_kjsu.xlsx")

if not INPUT_FILE.exists():
    raise FileNotFoundError(
        f"File {INPUT_FILE.name!r} tidak ditemukan. "
        "Pastikan file Excel dan script berada di folder yang sama."
    )


def normalize_code(series):
    """Samakan Kode RS menjadi string tanpa .0 dan spasi."""
    return (
        series.astype("string")
        .str.strip()
        .str.replace(r"\.0$", "", regex=True)
    )


def make_unique(names):
    """Membuat nama kolom unik tanpa menghilangkan informasi header asli."""
    seen = {}
    result = []
    for x in names:
        base = "Unnamed" if pd.isna(x) else str(x).strip()
        if base not in seen:
            seen[base] = 1
            result.append(base)
        else:
            seen[base] += 1
            result.append(f"{base}_{seen[base]}")
    return result


# ============================================================
# 1. BACA FILE MENTAH
# ============================================================
sdm_raw = pd.read_excel(INPUT_FILE, sheet_name="Data SDM", header=None)
alkes_raw = pd.read_excel(INPUT_FILE, sheet_name="Data Alkes", header=None)
bpjs_raw = pd.read_excel(INPUT_FILE, sheet_name="data BPJS", header=None)

print("Ukuran data mentah:")
print("SDM  :", sdm_raw.shape)
print("Alkes:", alkes_raw.shape)
print("BPJS :", bpjs_raw.shape)


# ============================================================
# 2. BERSIHKAN DATA SDM
# ============================================================
meta_headers = [
    "Kode_RS",
    "Rumah_Sakit",
    "Jenis",
    "Jenis_Grup",
    "Kelas",
    "Penyelenggara",
    "Penyelenggara_Kategori",
    "Provinsi",
    "Kab_Kota",
]

staff_raw_names = list(sdm_raw.iloc[5, 9:35])
staff_headers = make_unique(staff_raw_names)

sdm = sdm_raw.iloc[6:, :35].copy()
sdm.columns = meta_headers + staff_headers

sdm = sdm[sdm["Kode_RS"].notna()].copy()
sdm["Kode_RS"] = normalize_code(sdm["Kode_RS"])

# Ubah seluruh kolom SDM menjadi numerik.
for c in staff_headers:
    sdm[c] = pd.to_numeric(sdm[c], errors="coerce")

# Ada beberapa nama SDM yang muncul lebih dari sekali.
# Untuk TOTAL SDM, kolom duplikat yang isinya 100% identik hanya dihitung sekali.
staff_total_cols = []
first_by_original_name = {}

for unique_col, original_name in zip(staff_headers, staff_raw_names):
    original_name = str(original_name).strip()

    if original_name not in first_by_original_name:
        first_by_original_name[original_name] = unique_col
        staff_total_cols.append(unique_col)
    else:
        first_col = first_by_original_name[original_name]

        # Jika dua kolom berlabel sama ternyata berbeda isinya,
        # keduanya tetap dipertahankan dalam perhitungan.
        if not sdm[unique_col].equals(sdm[first_col]):
            staff_total_cols.append(unique_col)

sdm["Total_SDM_Tercatat"] = sdm[staff_total_cols].sum(axis=1, min_count=1)
sdm["Jumlah_Jenis_SDM_Tersedia"] = sdm[staff_total_cols].gt(0).sum(axis=1)
sdm["Kelengkapan_Data_SDM_pct"] = (
    sdm[staff_total_cols].notna().mean(axis=1) * 100
).round(2)


# ============================================================
# 3. BERSIHKAN DATA ALKES
# ============================================================
alkes_headers = [
    "No",
    "Provinsi",
    "Kab_Kota",
    "Rumah_Sakit",
    "Kode_RS",
] + [str(x).strip() for x in alkes_raw.iloc[1, 5:25]]

alkes = alkes_raw.iloc[2:, :25].copy()
alkes.columns = alkes_headers
alkes = alkes[alkes["Kode_RS"].notna()].copy()
alkes["Kode_RS"] = normalize_code(alkes["Kode_RS"])

equipment_cols = alkes_headers[5:]

for c in equipment_cols:
    alkes[c] = pd.to_numeric(alkes[c], errors="coerce")

# Cek duplikasi RS pada data Alkes.
alkes_dup_count = alkes.groupby("Kode_RS").size().rename("Jumlah_Record_Alkes_Raw")

# Satu Kode RS yang muncul lebih dari sekali tidak dijumlahkan karena dapat
# menggandakan unit. Untuk basis data RS, digunakan nilai maksimum yang
# dilaporkan per jenis alat, sambil tetap memberi flag duplikasi.
agg_dict = {
    "Provinsi": "first",
    "Kab_Kota": "first",
    "Rumah_Sakit": "first",
}
agg_dict.update({c: "max" for c in equipment_cols})

alkes_rs = alkes.groupby("Kode_RS", as_index=False).agg(agg_dict)
alkes_rs = alkes_rs.merge(
    alkes_dup_count.reset_index(),
    on="Kode_RS",
    how="left"
)

alkes_rs["Duplikat_Alkes_Raw"] = alkes_rs["Jumlah_Record_Alkes_Raw"].gt(1)
alkes_rs["Total_Unit_Alkes_Tercatat"] = alkes_rs[equipment_cols].sum(
    axis=1, min_count=1
)
alkes_rs["Jumlah_Jenis_Alkes_Tersedia"] = alkes_rs[equipment_cols].gt(0).sum(axis=1)
alkes_rs["Kelengkapan_Data_Alkes_pct"] = (
    alkes_rs[equipment_cols].notna().mean(axis=1) * 100
).round(2)


# ============================================================
# 4. NORMALISASI DATA BPJS (WIDE/BLOK -> LONG)
# ============================================================
bpjs_parts = []

# Setiap layanan menggunakan 3 kolom data + 1 kolom pemisah kosong.
for start in range(0, bpjs_raw.shape[1], 4):
    if start >= bpjs_raw.shape[1]:
        break

    service = bpjs_raw.iloc[0, start]

    if pd.isna(service):
        continue

    part = bpjs_raw.iloc[2:, [start, start + 1, start + 2]].copy()
    part.columns = ["Nama_RS_BPJS", "Kode_RS", "Status_BPJS"]
    part["Layanan_BPJS"] = str(service).strip()

    part = part[
        part[["Nama_RS_BPJS", "Kode_RS", "Status_BPJS"]]
        .notna()
        .any(axis=1)
    ].copy()

    part["Kode_RS"] = normalize_code(part["Kode_RS"])
    bpjs_parts.append(part)

bpjs_long = pd.concat(bpjs_parts, ignore_index=True)

# Hilangkan record ganda yang benar-benar identik.
bpjs_long = bpjs_long.drop_duplicates(
    subset=["Layanan_BPJS", "Nama_RS_BPJS", "Kode_RS", "Status_BPJS"]
).reset_index(drop=True)

# Tambahkan wilayah berdasarkan master SDM.
geo_lookup = sdm[
    ["Kode_RS", "Rumah_Sakit", "Provinsi", "Kab_Kota"]
].drop_duplicates("Kode_RS")

bpjs_long = bpjs_long.merge(
    geo_lookup,
    on="Kode_RS",
    how="left"
)

bpjs_long["Status_Sudah_BPJS"] = (
    bpjs_long["Status_BPJS"].eq("Sudah kerjasama BPJS").astype(int)
)

# Pivot status BPJS agar bisa ditempel ke master RS.
bpjs_pivot = (
    bpjs_long
    .pivot_table(
        index="Kode_RS",
        columns="Layanan_BPJS",
        values="Status_BPJS",
        aggfunc="first"
    )
    .add_prefix("BPJS_")
    .reset_index()
)


# ============================================================
# 5. SUSUN MASTER DATABASE PER RUMAH SAKIT
# ============================================================
master = sdm.copy()

alkes_for_merge = alkes_rs[
    ["Kode_RS"]
    + equipment_cols
    + [
        "Jumlah_Record_Alkes_Raw",
        "Duplikat_Alkes_Raw",
        "Total_Unit_Alkes_Tercatat",
        "Jumlah_Jenis_Alkes_Tersedia",
        "Kelengkapan_Data_Alkes_pct",
    ]
].copy()

alkes_for_merge["Ada_Data_Alkes"] = 1

master = master.merge(
    alkes_for_merge,
    on="Kode_RS",
    how="left"
)

master["Ada_Data_Alkes"] = master["Ada_Data_Alkes"].fillna(0).astype(int)

master = master.merge(
    bpjs_pivot,
    on="Kode_RS",
    how="left"
)

bpjs_status_cols = [c for c in master.columns if c.startswith("BPJS_")]

master["Jumlah_Layanan_BPJS_Tercatat"] = master[bpjs_status_cols].notna().sum(axis=1)
master["Jumlah_Layanan_BPJS_Sudah"] = (
    master[bpjs_status_cols]
    .eq("Sudah kerjasama BPJS")
    .sum(axis=1)
)

master["Persen_Layanan_BPJS_Sudah"] = np.where(
    master["Jumlah_Layanan_BPJS_Tercatat"] > 0,
    master["Jumlah_Layanan_BPJS_Sudah"]
    / master["Jumlah_Layanan_BPJS_Tercatat"]
    * 100,
    np.nan,
).round(2)


# ============================================================
# 6. STATISTIK DESKRIPTIF SDM
# ============================================================
sdm_summary_rows = []

for c in staff_total_cols:
    valid_n = int(sdm[c].notna().sum())
    available_n = int(sdm[c].gt(0).sum())
    total_value = float(sdm[c].sum(skipna=True))
    median_value = float(sdm[c].median(skipna=True)) if valid_n else np.nan

    sdm_summary_rows.append({
        "Jenis_SDM": c,
        "Total_SDM": total_value,
        "RS_Tercatat": valid_n,
        "RS_dengan_SDM": available_n,
        "Persen_RS_dengan_SDM_dari_yang_Tercatat": (
            available_n / valid_n * 100 if valid_n else np.nan
        ),
        "Median_per_RS_Tercatat": median_value,
        "Data_Kosong": int(sdm[c].isna().sum()),
    })

summary_sdm = pd.DataFrame(sdm_summary_rows)
summary_sdm["Persen_RS_dengan_SDM_dari_yang_Tercatat"] = (
    summary_sdm["Persen_RS_dengan_SDM_dari_yang_Tercatat"].round(2)
)


# ============================================================
# 7. STATISTIK DESKRIPTIF ALKES
# ============================================================
alkes_summary_rows = []

for c in equipment_cols:
    valid_n = int(alkes_rs[c].notna().sum())
    available_n = int(alkes_rs[c].gt(0).sum())
    total_units = float(alkes_rs[c].sum(skipna=True))

    alkes_summary_rows.append({
        "Jenis_Alkes": c,
        "Total_Unit": total_units,
        "RS_Tercatat": valid_n,
        "RS_dengan_Alkes": available_n,
        "Persen_RS_dengan_Alkes_dari_yang_Tercatat": (
            available_n / valid_n * 100 if valid_n else np.nan
        ),
        "Data_Kosong": int(alkes_rs[c].isna().sum()),
        "Persen_Data_Kosong": (
            alkes_rs[c].isna().mean() * 100
        ),
    })

summary_alkes = pd.DataFrame(alkes_summary_rows)
summary_alkes["Persen_RS_dengan_Alkes_dari_yang_Tercatat"] = (
    summary_alkes["Persen_RS_dengan_Alkes_dari_yang_Tercatat"].round(2)
)
summary_alkes["Persen_Data_Kosong"] = summary_alkes["Persen_Data_Kosong"].round(2)


# ============================================================
# 8. RINGKASAN BPJS
# ============================================================
summary_bpjs = (
    bpjs_long
    .groupby(["Layanan_BPJS", "Status_BPJS"], dropna=False)
    .size()
    .rename("Jumlah_RS")
    .reset_index()
)

bpjs_service = (
    bpjs_long
    .groupby("Layanan_BPJS")
    .agg(
        RS_Tercatat=("Kode_RS", "nunique"),
        RS_Sudah_BPJS=("Status_Sudah_BPJS", "sum"),
    )
    .reset_index()
)

bpjs_service["RS_Belum_BPJS"] = (
    bpjs_service["RS_Tercatat"] - bpjs_service["RS_Sudah_BPJS"]
)

bpjs_service["Persen_Sudah_BPJS"] = (
    bpjs_service["RS_Sudah_BPJS"]
    / bpjs_service["RS_Tercatat"]
    * 100
).round(2)


# ============================================================
# 9. RINGKASAN PER PROVINSI DAN KAB/KOTA
# ============================================================
summary_provinsi = (
    master
    .groupby("Provinsi", dropna=False)
    .agg(
        Jumlah_RS=("Kode_RS", "nunique"),
        Total_SDM_Tercatat=("Total_SDM_Tercatat", "sum"),
        Median_Total_SDM_per_RS=("Total_SDM_Tercatat", "median"),
        RS_dengan_Data_Alkes=("Ada_Data_Alkes", "sum"),
        Total_Unit_Alkes_Tercatat=("Total_Unit_Alkes_Tercatat", "sum"),
        RS_dengan_Data_BPJS=("Jumlah_Layanan_BPJS_Tercatat",
                             lambda s: int((s > 0).sum())),
        Total_Layanan_BPJS_Sudah=("Jumlah_Layanan_BPJS_Sudah", "sum"),
    )
    .reset_index()
)

summary_provinsi["Persen_RS_dengan_Data_Alkes"] = (
    summary_provinsi["RS_dengan_Data_Alkes"]
    / summary_provinsi["Jumlah_RS"]
    * 100
).round(2)

summary_kabkota = (
    master
    .groupby(["Provinsi", "Kab_Kota"], dropna=False)
    .agg(
        Jumlah_RS=("Kode_RS", "nunique"),
        Total_SDM_Tercatat=("Total_SDM_Tercatat", "sum"),
        Median_Total_SDM_per_RS=("Total_SDM_Tercatat", "median"),
        RS_dengan_Data_Alkes=("Ada_Data_Alkes", "sum"),
        Total_Unit_Alkes_Tercatat=("Total_Unit_Alkes_Tercatat", "sum"),
        RS_dengan_Data_BPJS=("Jumlah_Layanan_BPJS_Tercatat",
                             lambda s: int((s > 0).sum())),
        Total_Layanan_BPJS_Sudah=("Jumlah_Layanan_BPJS_Sudah", "sum"),
    )
    .reset_index()
)

summary_kabkota["Persen_RS_dengan_Data_Alkes"] = (
    summary_kabkota["RS_dengan_Data_Alkes"]
    / summary_kabkota["Jumlah_RS"]
    * 100
).round(2)


# Ringkasan BPJS per provinsi dan layanan.
summary_bpjs_prov = (
    bpjs_long
    .dropna(subset=["Provinsi"])
    .groupby(["Provinsi", "Layanan_BPJS"])
    .agg(
        RS_Tercatat=("Kode_RS", "nunique"),
        RS_Sudah_BPJS=("Status_Sudah_BPJS", "sum"),
    )
    .reset_index()
)

summary_bpjs_prov["Persen_Sudah_BPJS"] = (
    summary_bpjs_prov["RS_Sudah_BPJS"]
    / summary_bpjs_prov["RS_Tercatat"]
    * 100
).round(2)


# ============================================================
# 10. CATATAN KUALITAS DATA
# ============================================================
quality_notes = pd.DataFrame([
    {
        "Temuan": "Duplikasi data Alkes",
        "Detail": (
            f"{int((alkes_dup_count > 1).sum())} Kode RS muncul lebih dari sekali "
            "pada data Alkes. Untuk master RS digunakan nilai maksimum per jenis "
            "alat agar unit tidak terhitung ganda."
        ),
    },
    {
        "Temuan": "Duplikasi identik BPJS",
        "Detail": (
            "Record BPJS yang identik pada layanan, nama RS, Kode RS dan status "
            "dihapus sebelum analisis."
        ),
    },
    {
        "Temuan": "Kode RS BPJS tidak cocok",
        "Detail": (
            "Sebagian kecil Kode RS pada data BPJS tidak ditemukan pada master SDM. "
            "Baris tersebut tetap dipertahankan pada BPJS_Long tetapi wilayahnya "
            "akan kosong."
        ),
    },
    {
        "Temuan": "Nilai kosong",
        "Detail": (
            "Nilai NaN/kosong tidak otomatis diubah menjadi nol. Nol berarti nilai "
            "yang memang tercatat 0, sedangkan NaN diperlakukan sebagai data tidak "
            "tercatat/tidak tersedia."
        ),
    },
    {
        "Temuan": "Header SDM berulang",
        "Detail": (
            "Beberapa label SDM muncul lebih dari sekali. Kolom yang berlabel sama "
            "dan isinya 100% identik hanya dihitung sekali pada Total_SDM_Tercatat; "
            "kolom yang isinya berbeda tetap dipertahankan."
        ),
    },
])


# ============================================================
# 11. EKSPOR HASIL
# ============================================================
with pd.ExcelWriter(OUTPUT_FILE, engine="openpyxl") as writer:
    master.to_excel(writer, sheet_name="Master_RS", index=False)
    bpjs_long.to_excel(writer, sheet_name="BPJS_Long", index=False)
    alkes_rs.to_excel(writer, sheet_name="Alkes_per_RS", index=False)
    summary_provinsi.to_excel(writer, sheet_name="Summary_Provinsi", index=False)
    summary_kabkota.to_excel(writer, sheet_name="Summary_KabKota", index=False)
    summary_sdm.to_excel(writer, sheet_name="Summary_SDM", index=False)
    summary_alkes.to_excel(writer, sheet_name="Summary_Alkes", index=False)
    bpjs_service.to_excel(writer, sheet_name="Summary_BPJS", index=False)
    summary_bpjs_prov.to_excel(writer, sheet_name="BPJS_Provinsi", index=False)
    quality_notes.to_excel(writer, sheet_name="Data_Quality", index=False)

# CSV master juga dibuat agar mudah dibaca dashboard/aplikasi lain.
master.to_csv("master_rs_kjsu.csv", index=False, encoding="utf-8-sig")
bpjs_long.to_csv("bpjs_long_kjsu.csv", index=False, encoding="utf-8-sig")

print("\nSelesai.")
print(f"Master RS             : {len(master):,} baris")
print(f"RS unik data Alkes    : {alkes_rs['Kode_RS'].nunique():,}")
print(f"Record BPJS long      : {len(bpjs_long):,}")
print(f"Provinsi              : {master['Provinsi'].nunique(dropna=True):,}")
print(f"Kab/Kota              : {master['Kab_Kota'].nunique(dropna=True):,}")
print(f"Output Excel          : {OUTPUT_FILE.resolve()}")
print("Output CSV            : master_rs_kjsu.csv dan bpjs_long_kjsu.csv")
