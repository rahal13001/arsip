<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Archive;
use App\Models\Archivetype;
use App\Models\Filecode;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;



class ArchiveController extends Controller
{
    public function index(Request $request)
    {

        // 1. Start the base query with all necessary relationships
        $query = Archive::query()->with([
            'filecode',
            'archiveuser',
            'archiveaccess',
            'archivetype',
            'user',
            'accessclassification',
        ]);

        // 2. Replicate the complex search logic from your Filament table
        $query->when($request->input('search'), function (Builder $q, string $search) {
            if (str_contains($search, '/')) {
                $searchParts = explode('/', $search);
                return $q->where(function (Builder $subQ) use ($searchParts) {
                    if (!empty($searchParts[0])) {
                        $subQ->where('archive_number', 'like', '%' . $searchParts[0] . '%');
                    }
                    if (isset($searchParts[1]) && !empty($searchParts[1])) {
                        $subQ->whereHas('filecode', fn(Builder $codeQuery) => $codeQuery->where('file_code', 'like', '%' . $searchParts[1] . '%'));
                    }
                    if (isset($searchParts[2]) && !empty($searchParts[2])) {
                        $subQ->whereYear('date_input', $searchParts[2]);
                    }
                });
            }

            return $q->where(function (Builder $subQ) use ($search) {
                $subQ->where('archive_name', 'like', "%{$search}%")
                    ->orWhere('archive_number', 'like', "%{$search}%")
                    ->orWhereHas('filecode', fn(Builder $codeQuery) => $codeQuery->where('file_code', 'like', "%{$search}%"))
                    ->orWhereRaw("YEAR(date_input) like ?", ["%{$search}%"]);
            });
        });

        // 3. Replicate the filter logic
        $query->when($request->input('filecodes'), function (Builder $q, array $filecodes) {
            $q->whereIn('filecode_id', $filecodes);
        });
        $query->when($request->input('types'), function (Builder $q, array $types) {
            $q->whereIn('archivetype_id', $types);
        });
        $query->when($request->input('properties'), function(Builder $q, array $properties){
            $q->whereHas('archiveuser', fn(Builder $userQuery) => $userQuery->whereIn('archive_properties', $properties));
        });

        // 4. Handle sorting
        // THE FIX: Add `use ($request)` to make the $request variable available inside the closure.
        $query->when($request->input('sort'), function (Builder $q, string $sort) use ($request) {
            $direction = $request->input('direction', 'asc');
            // This list of sortable columns should match what your frontend can send.
            $sortableColumns = ['archive_name', 'date_input', 'properties', 'archive_status', 'archive_type', 'archive_number'];
            if (in_array($sort, $sortableColumns)) {
                $q->orderBy($sort, $direction);
            }
        }, function (Builder $q) {
            // Default sort order if none is specified
            $q->latest('date_input');
        });

        // 5. Paginate the results and format the data for the frontend
        $archives = $query->paginate(10)->withQueryString();

        return Inertia::render('Archive/Index', [
            // Pass the paginated data and ensure the 'through' callback has access to the full paginator object
            // by calling toArray() and then adding our custom data.
            'archives' => array_merge($archives->toArray(), [
                'data' => $archives->getCollection()->transform(fn($archive) => [
                    'id' => $archive->id,
                    'archive_number_formatted' => $archive->archive_number . '/' . optional($archive->filecode)->file_code . '/' . Carbon::parse($archive->date_input)->format('Y'),
                    'archive_name' => $archive->archive_name,
                    'archive_status' => optional($archive->archiveuser)->archive_status == 1 ? 'Permanen' : 'Musnah',
                    'skkad' => optional($archive->archiveaccess)->archive_access,
                    'archive_type' => optional($archive->archivetype)->archive_type,
                    'classification' => optional($archive->accessclassification)->access_classification,
                    'properties' => optional($archive->archiveuser)->archive_properties,
                    'date_input' => Carbon::parse($archive->date_input)->isoFormat('D MMMM YYYY'),
                ]),
            ]),
            // Pass the current filter values back to the view
            'filters' => [
                'search' => $request->input('search', null),
                'sortField' => $request->input('sort', 'date_input'),
                'sortOrder' => $request->input('direction', 'desc'),
            ],
            // Pass the options needed for the filter dropdowns
            'filterOptions' => [
                'filecodes' => Filecode::orderBy('file_code')->get(['id', 'file_code', 'code_name']),
                'types' => ArchiveType::orderBy('archive_type')->get(['id', 'archive_type']),
                'properties' => ['Aktif', 'Inaktif', 'Vital'],
            ],
        ]);
    }
}
