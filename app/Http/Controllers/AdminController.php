<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_products' => Barang::where('putri_status', '1')->count(),
            'low_stock'      => Barang::where('putri_status', '1')
                ->where('putri_stok', '<=', 10)
                ->count(),
            'total_value'    => Barang::where('putri_status', '1')
                ->selectRaw('SUM(putri_stok * putri_harga_jual) as total')
                ->value('total'),
            'pending_orders' => Pesanan::where('putri_status_pembayaran', 'pending')->count()
        ];

        $products = Barang::where('putri_status', '1')
            ->orderBy('putri_id_barang', 'desc')  // Ganti latest() dengan ini
            ->get();

        return view('admin.dashboard', compact('stats', 'products'));
    }

    public function dataBarang(Request $request)
    {
        $search = $request->search ?? '';

        $barang = Barang::when($search, function ($query) use ($search) {
            $query
                ->where('putri_nama_barang', 'like', "%{$search}%")
                ->orWhere('putri_satuan', 'like', "%{$search}%");
        })
            ->orderBy('putri_id_barang', 'desc')
            ->paginate(10);

        return view('admin.data_barang', compact('barang'));
    }

    public function tambahBarang()
    {
        return view('admin.tambah_barang');
    }

    public function storeBarang(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:60',
            'satuan'      => 'required|string|max:20',
            'harga_jual'  => 'required|numeric|min:0',
            'harga_beli'  => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Upload gambar
        $gambarPath = $request->file('gambar')->store('products', 'public');

        // Simpan data barang
        Barang::create([
            'putri_nama_barang' => $request->nama_barang,
            'putri_satuan'      => $request->satuan,
            'putri_harga_jual'  => $request->harga_jual,
            'putri_harga_beli'  => $request->harga_beli,
            'putri_stok'        => $request->stok,
            'putri_status'      => '1',
            'putri_gambar'      => $gambarPath
        ]);

        return redirect()
            ->route('admin.data-barang')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    public function editBarang($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.edit_barang', compact('barang'));
    }

    public function deleteBarang($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()
            ->route('admin.data-barang')
            ->with('success', 'Barang berhasil dihapus');
    }

    public function updateBarang(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:60',
            'satuan'      => 'required|string|max:20',
            'harga_jual'  => 'required|numeric|min:0',
            'harga_beli'  => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'status'      => 'required|in:0,1',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'putri_nama_barang' => $request->nama_barang,
            'putri_satuan'      => $request->satuan,
            'putri_harga_jual'  => $request->harga_jual,
            'putri_harga_beli'  => $request->harga_beli,
            'putri_stok'        => $request->stok,
            'putri_status'      => $request->status,
        ];

        // Jika ada upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($barang->putri_gambar) {
                Storage::disk('public')->delete($barang->putri_gambar);
            }
            $data['putri_gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $barang->update($data);

        return redirect()
            ->route('admin.data-barang')
            ->with('success', 'Barang berhasil diperbarui');
    }

    public function pesanan(Request $request)
    {
        $query = Pesanan::with('barang')
            ->when($request->status, function ($q) use ($request) {
                return $q->where('putri_status_pembayaran', $request->status);
            })
            ->when($request->search, function ($q) use ($request) {
                return $q
                    ->where('putri_nama_user', 'like', "%{$request->search}%")
                    ->orWhereHas('barang', function ($query) use ($request) {
                        $query->where('putri_nama_barang', 'like', "%{$request->search}%");
                    });
            })
            ->latest('putri_tanggal_pesan');

        $pesanan = $query->paginate(10)->withQueryString();

        return view('admin.pesanan', compact('pesanan'));
    }

    public function updateStatusPesanan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verifikasi,selesai'
        ]);

        $pesanan                          = Pesanan::findOrFail($id);
        $pesanan->putri_status_pembayaran = $request->status;
        $pesanan->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function transaksi(Request $request)
    {
        $query = Pesanan::with('barang')
            ->when($request->tanggal_mulai, function ($q) use ($request) {
                return $q->whereDate('putri_tanggal_pesan', '>=', $request->tanggal_mulai);
            })
            ->when($request->tanggal_akhir, function ($q) use ($request) {
                return $q->whereDate('putri_tanggal_pesan', '<=', $request->tanggal_akhir);
            })
            ->where('putri_status_pembayaran', 'selesai')
            ->latest('putri_tanggal_pesan');

        $transaksi = $query->paginate(10)->withQueryString();

        return view('admin.transaksi', compact('transaksi'));
    }

    public function exportTransaksi(Request $request)
    {
        $query = Pesanan::with('barang')
            ->when($request->tanggal_mulai, function ($q) use ($request) {
                return $q->whereDate('putri_tanggal_pesan', '>=', $request->tanggal_mulai);
            })
            ->when($request->tanggal_akhir, function ($q) use ($request) {
                return $q->whereDate('putri_tanggal_pesan', '<=', $request->tanggal_akhir);
            })
            ->where('putri_status_pembayaran', 'selesai')
            ->latest('putri_tanggal_pesan')
            ->get();

        // Export ke Excel
        $filename = 'transaksi_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Nama User');
        $sheet->setCellValue('D1', 'Barang');
        $sheet->setCellValue('E1', 'Jumlah');
        $sheet->setCellValue('F1', 'Total Harga');
        $sheet->setCellValue('G1', 'Status');

        // Data
        $row = 2;
        foreach ($query as $item) {
            $sheet->setCellValue('A' . $row, $row - 1);
            $sheet->setCellValue('B' . $row, \Carbon\Carbon::parse($item->putri_tanggal_pesan)->format('d/m/Y H:i'));
            $sheet->setCellValue('C' . $row, $item->putri_nama_user);
            $sheet->setCellValue('D' . $row, $item->barang ? $item->barang->putri_nama_barang : 'Data barang tidak tersedia');
            $sheet->setCellValue('E' . $row, $item->putri_jumlah_barang);
            $sheet->setCellValue('F' . $row, $item->putri_total_harga);
            $sheet->setCellValue('G' . $row, ucfirst($item->putri_status_pembayaran));
            $row++;
        }

        // AutoSize kolom
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function laporan(Request $request)
    {
        // Validasi input untuk start_date dan end_date
        $validatedData = $request->validate([
            'start_date' => 'nullable|date',  // Tanggal mulai
            'end_date'   => 'nullable|date',  // Tanggal akhir
        ]);

        // Ambil start_date dan end_date dari request
        $startDate = $validatedData['start_date'] ?? null;
        $endDate   = $validatedData['end_date'] ?? null;

        // Fetch data laporan berdasarkan tanggal
        $laporan = $this->fetchLaporan($startDate, $endDate);

        // Hitung statistik berdasarkan laporan
        $statistik = $this->generateStatistik($laporan);

        // Kirim data ke view
        return view('admin.laporan', compact('laporan', 'statistik', 'startDate', 'endDate'));
    }

    private function fetchLaporan($startDate, $endDate)
    {
        return Pesanan::with('barang')
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('putri_tanggal_pesan', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('putri_tanggal_pesan', '<=', $endDate);
            })
            ->where('putri_status_pembayaran', 'selesai')
            ->latest('putri_tanggal_pesan')
            ->get();
    }

    private function generateStatistik($laporan)
    {
        return [
            'total_penjualan'     => $laporan->sum('putri_total_harga'),
            'total_transaksi'     => $laporan->count(),
            'rata_rata_penjualan' => $laporan->count() > 0 ? $laporan->sum('putri_total_harga') / $laporan->count() : 0,
            'total_produk'        => $laporan->sum('putri_jumlah_barang')
        ];
    }

    public function exportLaporan(Request $request)
    {
        // Ambil start_date dan end_date dari request
        $startDate = $request->start_date ?? null;
        $endDate   = $request->end_date ?? null;

        // Ambil data laporan
        $laporan = Pesanan::with('barang')
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('putri_tanggal_pesan', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('putri_tanggal_pesan', '<=', $endDate);
            })
            ->where('putri_status_pembayaran', 'selesai')
            ->latest('putri_tanggal_pesan')
            ->get();

        // Hitung statistik untuk laporan
        $statistik = $this->generateStatistik($laporan);

        // Nama file PDF
        $filename = 'Laporan_Penjualan_' . ($startDate ? $startDate : 'Semua') . '_to_' . ($endDate ? $endDate : 'Semua') . '.pdf';

        // Render ke view PDF
        $pdf = \PDF::loadView('admin.laporan_pdf', compact('laporan', 'statistik', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape');  // Set ukuran dan orientasi kertas

        // Unduh file PDF
        return $pdf->download($filename);
    }
}
