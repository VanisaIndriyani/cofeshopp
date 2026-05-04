<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminTableController extends Controller
{
    private function qrPng(string $url): string
    {
        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 512,
            margin: 16,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getString();
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $tables = Table::query()
            ->when($q !== '', fn ($qq) => $qq->where('code', 'like', "%{$q}%")->orWhere('name', 'like', "%{$q}%"))
            ->orderBy('code')
            ->paginate(20)
            ->withQueryString();

        return view('admin.tables.index', [
            'tables' => $tables,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:tables,code'],
            'name' => ['nullable', 'string', 'max:60'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Table::create([
            'code' => strtoupper(trim($data['code'])),
            'name' => $data['name'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Meja berhasil dibuat.']);
        return back();
    }

    public function update(Request $request, Table $table)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('tables', 'code')->ignore($table->id)],
            'name' => ['nullable', 'string', 'max:60'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $table->update([
            'code' => strtoupper(trim($data['code'])),
            'name' => $data['name'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Meja diperbarui.']);
        return back();
    }

    public function destroy(Request $request, Table $table)
    {
        $table->delete();
        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Meja dihapus.']);
        return back();
    }

    public function qr(Request $request, Table $table)
    {
        $url = route('table.menu', ['code' => $table->code]);
        $png = $this->qrPng($url);

        $filename = "QR-Meja-{$table->code}.png";

        if ($request->boolean('download')) {
            return response($png, 200)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
        }

        return response($png, 200)->header('Content-Type', 'image/png');
    }

    public function qrPublic(Request $request, string $code)
    {
        $code = strtoupper(trim($code));

        $table = Table::query()
            ->where('code', $code)
            ->firstOrFail();

        $url = route('table.menu', ['code' => $table->code]);
        $png = $this->qrPng($url);

        return response($png, 200)->header('Content-Type', 'image/png');
    }
}
