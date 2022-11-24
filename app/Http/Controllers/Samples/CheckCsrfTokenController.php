<?php
declare(strict_types=1);
namespace App\Http\Controllers\Samples;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;

class CheckCsrfTokenController extends Controller
{
    /** @var Session */
    public Session $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function index()
    {
        return view('samples.checkCsrfToken', [
            'csrfResult' => $this->session->get('csrfResult') ?? 'ng'
        ]);
    }

    public function update()
    {
        $this->session->flash('csrfResult', 'ok');
        return redirect()->route('checkCsrfToken');
    }
}
