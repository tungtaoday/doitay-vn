<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Services\CompanyCreationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Admin-only endpoints to create and manage seeded (fake) marketplace data.
 * All seeded records have is_seeded = 1 and never interact with real users.
 */
class SeedController extends Controller
{
    public function __construct(private CompanyCreationService $creator) {}

    // ── POST /admin/seed/users ────────────────────────────────────────────
    public function createUser(Request $request): JsonResponse
    {
        $data = $request->validate([
            'firstname'  => 'required|string|max:50',
            'lastname'   => 'required|string|max:100',
            'role'       => 'required|in:contractor,customer',
            'seed_batch' => 'nullable|date',
        ]);

        $email  = 'seed_' . Str::random(12) . '@seed.doitay.local';
        // Generate unique mobile to avoid collision with real users
        do {
            $mobile = '09' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (User::where('mobile', $mobile)->exists());

        $user = User::create([
            'firstname'        => $data['firstname'],
            'lastname'         => $data['lastname'],
            'name'             => $data['lastname'] . ' ' . $data['firstname'],
            'email'            => $email,
            'mobile'           => $mobile,
            'password'         => Hash::make(Str::random(32)),
            'ev'               => 1,
            'sv'               => 1,
            'profile_complete' => 1,
            'status'           => 1,
            'is_seeded'        => 1,
            'seed_batch'       => $data['seed_batch'] ?? now()->toDateString(),
        ]);

        return response()->json(['data' => $this->formatUser($user)], 201);
    }

    // ── POST /admin/seed/users/{id}/avatar ───────────────────────────────
    public function uploadAvatar(Request $request, int $id): JsonResponse
    {
        $request->validate(['avatar' => 'required|image|max:3072']);

        $user = User::findOrFail($id);
        if (! $user->is_seeded) {
            return response()->json(['error' => 'Not a seeded user'], 403);
        }

        $file     = $request->file('avatar');
        $filename = 'seed/avatars/' . $id . '_' . time() . '.' . $file->extension();
        Storage::disk('public')->put($filename, file_get_contents($file));

        $user->image = $filename;
        $user->save();

        return response()->json(['data' => ['image' => $filename]]);
    }

    // ── POST /admin/seed/companies ────────────────────────────────────────
    public function createCompany(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id'       => 'required|integer|exists:users,id',
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|min:10',
            'experience'    => 'required|integer|min:0|max:100',
            'category_id'   => 'required|integer|exists:categories,id',
            'city_code'     => 'required|string',
            'district_code' => 'required|string',
            'ward_code'     => 'nullable|string',
            'address'       => 'required|string|max:255',
            'tags'          => 'nullable|array',
            'services'      => 'nullable|array',
            'phone'         => 'nullable|string|max:20',
        ]);

        $user = User::findOrFail($data['user_id']);
        if (! $user->is_seeded) {
            return response()->json(['error' => 'User must be seeded'], 403);
        }

        try {
            $company = $this->creator->create($user, array_merge($data, [
                'email' => null,
            ]));

            // Mark as seeded and auto-approve (direct assignment avoids $fillable guard)
            $company->is_seeded = 1;
            $company->status    = 1; // APPROVED
            $company->save();

            return response()->json([
                'data' => [
                    'id'       => $company->id,
                    'name'     => $company->name,
                    'owner_id' => $user->id,
                ],
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    // ── POST /admin/seed/companies/{id}/image ────────────────────────────
    public function uploadCompanyImage(Request $request, int $id): JsonResponse
    {
        $request->validate(['image' => 'required|image|max:3072']);

        $company = Company::findOrFail($id);
        if (! $company->is_seeded) {
            return response()->json(['error' => 'Not a seeded company'], 403);
        }

        $file     = $request->file('image');
        $filename = 'seed/companies/' . $id . '_' . time() . '.' . $file->extension();
        Storage::disk('public')->put($filename, file_get_contents($file));

        $company->image = $filename;
        $company->save();

        return response()->json(['data' => ['image' => $filename]]);
    }

    // ── POST /admin/seed/appointments ────────────────────────────────────
    public function createAppointment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'company_id'       => 'required|integer|exists:companies,id',
            'customer_id'      => 'required|integer|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string',
            'status'           => 'required|in:pending,confirmed,completed,cancelled',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $company  = Company::findOrFail($data['company_id']);
        $customer = User::findOrFail($data['customer_id']);

        // Hard block: seed must only interact with seed
        if (! $company->is_seeded || ! $customer->is_seeded) {
            return response()->json(['error' => 'Cross-seed booking blocked'], 403);
        }
        // Prevent owner from booking their own company
        if ($company->user_id === $customer->id) {
            return response()->json(['error' => 'Owner cannot book own company'], 422);
        }

        $appt = DB::table('appointments')->insertGetId([
            'is_seeded'        => 1,
            'company_id'       => $data['company_id'],
            'user_id'          => $data['customer_id'],
            'recipient_name'   => $customer->name,
            'recipient_phone'  => $customer->mobile ?? '0900000000',
            'recipient_address'=> $customer->address ?? 'Địa chỉ test',
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time'],
            'status'           => $data['status'],
            'notes'            => $data['notes'] ?? '',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return response()->json(['data' => ['id' => $appt]], 201);
    }

    // ── GET /admin/seed/companies ─────────────────────────────────────────
    public function listCompanies(): JsonResponse
    {
        $companies = Company::where('is_seeded', 1)
            ->select('id', 'user_id', 'name', 'image', 'created_at')
            ->get()
            ->map(fn($c) => [
                'id'       => $c->id,
                'name'     => $c->name,
                'image'    => $c->image,
                'owner_id' => $c->user_id,
            ]);

        return response()->json(['data' => $companies]);
    }

    // ── GET /admin/seed/customers ─────────────────────────────────────────
    public function listCustomers(): JsonResponse
    {
        $customers = User::where('is_seeded', 1)
            ->doesntHave('companies')
            ->select('id', 'name', 'mobile', 'image', 'created_at')
            ->get()
            ->map(fn($u) => [
                'id'     => $u->id,
                'name'   => $u->name,
                'mobile' => $u->mobile,
                'image'  => $u->image,
            ]);

        return response()->json(['data' => $customers]);
    }

    // ── DELETE /admin/seed/flush ──────────────────────────────────────────
    public function flushAll(Request $request): JsonResponse
    {
        // Require explicit confirmation to prevent accidental calls
        if ($request->input('confirm') !== 'DELETE_ALL_SEED_DATA') {
            return response()->json([
                'error' => 'Pass confirm=DELETE_ALL_SEED_DATA to proceed',
            ], 422);
        }

        DB::transaction(function () {
            $seedUserIds = User::where('is_seeded', 1)->pluck('id');

            DB::table('appointments')->where('is_seeded', 1)->delete();
            Company::where('is_seeded', 1)->delete();
            User::whereIn('id', $seedUserIds)->delete();

            Log::info('seed.flush', ['flushed_users' => $seedUserIds->count()]);
        });

        return response()->json(['data' => ['flushed' => true]]);
    }

    // ── Run seed via Python agent (or queue) ─────────────────────────────
    public function run(Request $request): JsonResponse
    {
        $data = $request->validate([
            'contractors'  => 'nullable|integer|min:0|max:20',
            'customers'    => 'nullable|integer|min:0|max:20',
            'appointments' => 'nullable|integer|min:0|max:50',
        ]);

        $n = $data['contractors']  ?? 3;
        $m = $data['customers']    ?? 5;
        $a = $data['appointments'] ?? 8;

        // Resolve Python script path.
        // Priority 1: explicit env var SEED_SCRIPT_PATH (recommended for production).
        // Priority 2: sibling directory auto-detect (base_path() = .../core, go up 2).
        $candidates = array_filter([
            env('SEED_SCRIPT_PATH'),
            dirname(dirname(base_path())) . DIRECTORY_SEPARATOR . 'agent-system' . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'run_seed.py',
        ]);
        $script = null;
        foreach ($candidates as $path) {
            if ($path && file_exists($path)) { $script = $path; break; }
        }

        if (! $script) {
            return response()->json([
                'ok'      => false,
                'message' => 'Python seed script not found on this server. Run manually: python scripts/run_seed.py ' . implode(' ', [$n, $m, $a]),
            ], 422);
        }

        // Fire-and-forget: redirect stdout+stderr to a log file so PHP doesn't block.
        $logDir = storage_path('logs');
        $log    = $logDir . '/seed_' . now()->format('Ymd_His') . '.log';
        $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';

        // escapeshellarg prevents injection
        $cmd = implode(' ', [
            $python,
            escapeshellarg($script),
            (int) $n, (int) $m, (int) $a,
            '>>' . escapeshellarg($log) . ' 2>&1',
        ]);

        if (PHP_OS_FAMILY === 'Windows') {
            pclose(popen("start /B {$cmd}", 'r'));
        } else {
            exec("{$cmd} &");
        }

        return response()->json([
            'ok'      => true,
            'message' => "Seed job dispatched: {$n} contractors, {$m} customers, {$a} appointments.",
            'log'     => 'storage/logs/' . basename($log),
        ]);
    }

    // ── Stats ─────────────────────────────────────────────────────────────
    public function stats(): JsonResponse
    {
        return response()->json(['data' => [
            'seeded_contractors' => Company::where('is_seeded', 1)->count(),
            'seeded_customers'   => User::where('is_seeded', 1)->doesntHave('companies')->count(),
            'seeded_appointments'=> DB::table('appointments')->where('is_seeded', 1)->count(),
            'real_contractors'   => Company::where('is_seeded', 0)->where('status', 1)->count(),
            'real_customers'     => User::where('is_seeded', 0)->doesntHave('companies')->count(),
        ]]);
    }

    private function formatUser(User $user): array
    {
        return [
            'id'     => $user->id,
            'name'   => $user->name,
            'email'  => $user->email,
            'mobile' => $user->mobile,
            'image'  => $user->image,
        ];
    }
}
