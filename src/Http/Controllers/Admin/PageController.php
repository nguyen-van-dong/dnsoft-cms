<?php

namespace Module\Cms\Http\Controllers\Admin;

use Dnsoft\Core\Facades\MenuAdmin;
use Dnsoft\Media\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Module\Cms\Http\Requests\PageRequest;
use Module\Cms\Models\Post;
use Module\Cms\Repositories\PageRepositoryInterface;
use Module\Cms\Repositories\PostRepositoryInterface;

class PageController extends Controller
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = $this->pageRepository->paginate(20);
        return view('cms::admin.page.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        MenuAdmin::activeMenu('cms_page');
        $item = [];
        return view('cms::admin.page.create', compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PageRequest $request)
    {
        $product = $this->pageRepository->create($request->all());

        if ($request->input('continue')) {
            return redirect()
                ->route('cms.admin.page.edit', $product->id)
                ->with('success', __('cms::page.notification.created'));
        }

        return redirect()
            ->route('cms.admin.page.index')
            ->with('success', __('cms::page.notification.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        MenuAdmin::activeMenu('cms_page');
        $item = $this->pageRepository->getById($id);
        return view('cms::admin.page.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PageRequest  $request
     * @param $id
     * @return Response
     */
    public function update(PageRequest $request, $id)
    {
        $item = $this->pageRepository->updateById($request->all(), $id);

        if ($request->input('continue')) {
            return redirect()
                ->route('cms.admin.page.edit', $item->id)
                ->with('success', __('cms::page.notification.updated'));
        }

        return redirect()
            ->route('cms.admin.page.index')
            ->with('success', __('cms::page.notification.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $item = $this->pageRepository->delete($id);
        if ($request->ajax()) {
            Session::flash('success', __('cms::page.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.page.index')
            ->with('success', __('cms::page.notification.deleted'));
    }
}
