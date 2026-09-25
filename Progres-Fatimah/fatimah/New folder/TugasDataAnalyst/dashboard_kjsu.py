from pathlib import Path
import pandas as pd
import streamlit as st
import plotly.express as px

DATA_FILE = Path("hasil_analisis_kjsu.xlsx")

st.set_page_config(
    page_title="Dashboard KJSU",
    page_icon="🏥",
    layout="wide"
)

st.title("Dashboard Kondisi Eksisting KJSU")
st.caption("Ketersediaan SDM, Alat Kesehatan, dan Status Kerja Sama BPJS")

if not DATA_FILE.exists():
    st.error(
        "File hasil_analisis_kjsu.xlsx belum ada. "
        "Jalankan olah_data_kjsu.py terlebih dahulu."
    )
    st.stop()

master = pd.read_excel(DATA_FILE, sheet_name="Master_RS")
bpjs = pd.read_excel(DATA_FILE, sheet_name="BPJS_Long")
summary_sdm = pd.read_excel(DATA_FILE, sheet_name="Summary_SDM")
summary_alkes = pd.read_excel(DATA_FILE, sheet_name="Summary_Alkes")

# ------------------------------------------------------------
# Koordinat perkiraan pusat provinsi untuk visualisasi peta.
# Peta bersifat ringkasan wilayah, bukan lokasi presisi RS.
# ------------------------------------------------------------
province_centroids = {
    "Aceh": (4.70, 96.75),
    "Sumatera Utara": (2.50, 99.00),
    "Sumatera Barat": (-0.85, 100.46),
    "Riau": (0.50, 101.45),
    "Jambi": (-1.61, 103.61),
    "Sumatera Selatan": (-3.00, 104.75),
    "Bengkulu": (-3.80, 102.27),
    "Lampung": (-4.56, 105.41),
    "Kepulauan Bangka Belitung": (-2.74, 106.44),
    "Kepulauan Riau": (3.95, 108.14),
    "DKI Jakarta": (-6.20, 106.85),
    "Jawa Barat": (-6.92, 107.60),
    "Jawa Tengah": (-7.15, 110.14),
    "DI Yogyakarta": (-7.80, 110.37),
    "Jawa Timur": (-7.54, 112.24),
    "Banten": (-6.40, 106.10),
    "Bali": (-8.34, 115.09),
    "Nusa Tenggara Barat": (-8.65, 117.36),
    "Nusa Tenggara Timur": (-8.66, 121.08),
    "Kalimantan Barat": (-0.28, 111.48),
    "Kalimantan Tengah": (-1.68, 113.38),
    "Kalimantan Selatan": (-3.09, 115.28),
    "Kalimantan Timur": (0.54, 116.42),
    "Kalimantan Utara": (3.07, 116.04),
    "Sulawesi Utara": (0.62, 123.98),
    "Sulawesi Tengah": (-1.43, 121.45),
    "Sulawesi Selatan": (-3.67, 119.97),
    "Sulawesi Tenggara": (-4.14, 122.17),
    "Gorontalo": (0.70, 122.45),
    "Sulawesi Barat": (-2.68, 119.23),
    "Maluku": (-3.24, 130.15),
    "Maluku Utara": (1.57, 127.81),
    "Papua Barat": (-1.34, 133.17),
    "Papua": (-4.27, 138.08),
    "Papua Barat Daya": (-1.30, 131.30),
    "Papua Selatan": (-7.50, 139.50),
    "Papua Tengah": (-3.60, 136.30),
    "Papua Pegunungan": (-4.10, 138.95),
}

# ------------------------------------------------------------
# FILTER
# ------------------------------------------------------------
with st.sidebar:
    st.header("Filter")

    prov_options = sorted(master["Provinsi"].dropna().astype(str).unique())
    selected_prov = st.multiselect(
        "Provinsi",
        prov_options,
        default=[]
    )

filtered = master.copy()

if selected_prov:
    filtered = filtered[filtered["Provinsi"].isin(selected_prov)]

kab_options = sorted(filtered["Kab_Kota"].dropna().astype(str).unique())

with st.sidebar:
    selected_kab = st.multiselect(
        "Kab/Kota",
        kab_options,
        default=[]
    )

if selected_kab:
    filtered = filtered[filtered["Kab_Kota"].isin(selected_kab)]

rs_options = sorted(filtered["Rumah_Sakit"].dropna().astype(str).unique())

with st.sidebar:
    selected_rs = st.multiselect(
        "Rumah Sakit",
        rs_options,
        default=[]
    )

if selected_rs:
    filtered = filtered[filtered["Rumah_Sakit"].isin(selected_rs)]


# ------------------------------------------------------------
# KPI
# ------------------------------------------------------------
jumlah_rs = filtered["Kode_RS"].nunique()
total_sdm = filtered["Total_SDM_Tercatat"].sum(skipna=True)
rs_alkes = int(filtered["Ada_Data_Alkes"].fillna(0).sum())
total_alkes = filtered["Total_Unit_Alkes_Tercatat"].sum(skipna=True)

bpjs_recorded = filtered["Jumlah_Layanan_BPJS_Tercatat"].sum()
bpjs_yes = filtered["Jumlah_Layanan_BPJS_Sudah"].sum()
bpjs_pct = (bpjs_yes / bpjs_recorded * 100) if bpjs_recorded else 0

c1, c2, c3, c4, c5 = st.columns(5)
c1.metric("Jumlah RS", f"{jumlah_rs:,}")
c2.metric("Total SDM tercatat", f"{total_sdm:,.0f}")
c3.metric("RS dengan data Alkes", f"{rs_alkes:,}")
c4.metric("Total Unit Alkes Tercatat", f"{total_alkes:,.0f}", help="Total unit alat kesehatan pada rumah sakit yang memiliki data alkes.")
c5.metric("Kerja Sama BPJS dari Layanan Tercatat", f"{bpjs_pct:.1f}%", help="Persentase status Sudah kerjasama BPJS dari seluruh record layanan BPJS yang tercatat pada hasil filter.")

st.divider()


# ------------------------------------------------------------
# PETA PROVINSI
# ------------------------------------------------------------
prov_map = (
    filtered.groupby("Provinsi", as_index=False)
    .agg(
        Jumlah_RS=("Kode_RS", "nunique"),
        Total_SDM=("Total_SDM_Tercatat", "sum"),
        Total_Alkes=("Total_Unit_Alkes_Tercatat", "sum"),
    )
)

prov_map["Lat"] = prov_map["Provinsi"].map(
    lambda x: province_centroids.get(x, (None, None))[0]
)
prov_map["Lon"] = prov_map["Provinsi"].map(
    lambda x: province_centroids.get(x, (None, None))[1]
)
prov_map = prov_map.dropna(subset=["Lat", "Lon"])

st.subheader("Peta Ringkasan per Provinsi")
if not prov_map.empty:
    fig_map = px.scatter_geo(
        prov_map,
        lat="Lat",
        lon="Lon",
        size="Jumlah_RS",
        color="Total_Alkes",
        hover_name="Provinsi",
        hover_data={
            "Jumlah_RS": True,
            "Total_SDM": ":,.0f",
            "Total_Alkes": ":,.0f",
            "Lat": False,
            "Lon": False,
        },
        size_max=35,
        projection="mercator",
    )

    fig_map.update_geos(
        lataxis_range=[-12, 7],
        lonaxis_range=[94, 142],
        showland=True,
        showcountries=True,
        showcoastlines=True,
    )
    fig_map.update_layout(height=500, margin=dict(l=0, r=0, t=10, b=0))
    st.plotly_chart(fig_map, use_container_width=True)
else:
    st.info("Tidak ada provinsi yang dapat dipetakan untuk filter saat ini.")


# ------------------------------------------------------------
# GRAFIK WILAYAH
# ------------------------------------------------------------
left, right = st.columns(2)

with left:
    st.subheader("10 Provinsi dengan Jumlah RS Terbanyak")
    prov_chart = (
        filtered.groupby("Provinsi", as_index=False)["Kode_RS"]
        .nunique()
        .rename(columns={"Kode_RS": "Jumlah_RS"})
        .sort_values("Jumlah_RS", ascending=False)
        .head(10)
    )
    fig = px.bar(
        prov_chart.sort_values("Jumlah_RS"),
        x="Jumlah_RS",
        y="Provinsi",
        orientation="h"
    )
    st.plotly_chart(fig, use_container_width=True)

with right:
    st.subheader("Distribusi Kelas Rumah Sakit")
    kelas = (
        filtered["Kelas"]
        .fillna("Tidak tercatat")
        .value_counts()
        .rename_axis("Kelas")
        .reset_index(name="Jumlah_RS")
    )
    fig = px.pie(kelas, names="Kelas", values="Jumlah_RS", hole=0.4)
    st.plotly_chart(fig, use_container_width=True)


# ------------------------------------------------------------
# SDM DAN ALKES
# ------------------------------------------------------------
st.subheader("Kondisi SDM")

sdm_cols = list(summary_sdm["Jenis_SDM"].astype(str))
selected_sdm = st.selectbox(
    "Pilih jenis SDM",
    sdm_cols,
    index=0,
    key="sdm_selector"
)

if selected_sdm in filtered.columns:
    sdm_region = (
        filtered.groupby("Provinsi", as_index=False)[selected_sdm]
        .sum(min_count=1)
        .rename(columns={selected_sdm: "Jumlah"})
        .sort_values("Jumlah", ascending=False)
        .head(15)
    )

    fig = px.bar(
        sdm_region.sort_values("Jumlah"),
        x="Jumlah",
        y="Provinsi",
        orientation="h",
        title=f"15 Provinsi Teratas — {selected_sdm}"
    )
    st.plotly_chart(fig, use_container_width=True)


st.subheader("Kondisi Alat Kesehatan")

equipment_cols = list(summary_alkes["Jenis_Alkes"].astype(str))
selected_alkes = st.selectbox(
    "Pilih jenis alat kesehatan",
    equipment_cols,
    index=0,
    key="alkes_selector"
)

if selected_alkes in filtered.columns:
    alat_region = (
        filtered.groupby("Provinsi", as_index=False)[selected_alkes]
        .sum(min_count=1)
        .rename(columns={selected_alkes: "Jumlah_Unit"})
        .sort_values("Jumlah_Unit", ascending=False)
        .head(15)
    )

    fig = px.bar(
        alat_region.sort_values("Jumlah_Unit"),
        x="Jumlah_Unit",
        y="Provinsi",
        orientation="h",
        title=f"15 Provinsi Teratas — {selected_alkes}"
    )
    st.plotly_chart(fig, use_container_width=True)


# ------------------------------------------------------------
# BPJS
# ------------------------------------------------------------
st.subheader("Status Kerja Sama BPJS per Layanan")

filtered_codes = set(filtered["Kode_RS"].dropna().astype(str))
bpjs_filtered = bpjs[bpjs["Kode_RS"].astype(str).isin(filtered_codes)].copy()

if not bpjs_filtered.empty:
    bpjs_chart = (
        bpjs_filtered
        .groupby(["Layanan_BPJS", "Status_BPJS"])
        .size()
        .reset_index(name="Jumlah_RS")
    )

    fig = px.bar(
        bpjs_chart,
        x="Layanan_BPJS",
        y="Jumlah_RS",
        color="Status_BPJS",
        barmode="group"
    )
    st.plotly_chart(fig, use_container_width=True)
else:
    st.info("Tidak ada data BPJS pada filter saat ini.")


# ------------------------------------------------------------
# TABEL DETAIL
# ------------------------------------------------------------
st.subheader("Tabel Basis Data Rumah Sakit")

base_cols = [
    "Kode_RS",
    "Rumah_Sakit",
    "Provinsi",
    "Kab_Kota",
    "Kelas",
    "Penyelenggara_Kategori",
    "Total_SDM_Tercatat",
    "Jumlah_Jenis_SDM_Tersedia",
    "Ada_Data_Alkes",
    "Total_Unit_Alkes_Tercatat",
    "Jumlah_Jenis_Alkes_Tersedia",
    "Jumlah_Layanan_BPJS_Tercatat",
    "Jumlah_Layanan_BPJS_Sudah",
    "Persen_Layanan_BPJS_Sudah",
]

show_cols = [c for c in base_cols if c in filtered.columns]

# Data untuk tampilan tabel saja.
# Missing value tetap NaN pada data asli, tetapi ditampilkan sebagai "Tidak tercatat".
display_table = (
    filtered[show_cols]
    .sort_values(
        ["Provinsi", "Kab_Kota", "Rumah_Sakit"],
        na_position="last"
    )
    .copy()
)
display_table = display_table.astype(object).where(
    pd.notna(display_table),
    "Tidak tercatat"
)

st.dataframe(
    display_table,
    use_container_width=True,
    hide_index=True
)

st.download_button(
    "Download data hasil filter (CSV)",
    filtered.to_csv(index=False).encode("utf-8-sig"),
    file_name="dashboard_kjsu_filtered.csv",
    mime="text/csv",
)
