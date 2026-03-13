<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // ── Liste ──────────────────────────────────────────────────────────────
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $payments = match($user->type) {
            'admin' => Payment::with(['client', 'contrat.assurance'])->latest()->paginate(15),
            default => Payment::where('client_id', $user->id)
                              ->with('contrat.assurance')
                              ->latest()
                              ->paginate(15),
        };

        $view = match($user->type) {
            'admin' => 'admin.payments.index',
            default => 'client.payments.index',
        };

        return view($view, compact('payments'));
    }

    // ── Détail reçu ───────────────────────────────────────────────────────
    public function show(Payment $payment)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Seul le client propriétaire ou un admin peut voir
        abort_unless(
            $payment->client_id === $user->id || $user->type === 'admin',
            403
        );

        $payment->load('contrat.assurance');

        return view('client.payments.show', compact('payment'));
    }

    // ── Initier un paiement ───────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'contrat_id'    => ['required', 'exists:contrats,id'],
            'methode'       => ['required', 'in:wave,orange_money,free_money,simulation'],
            'numero_payeur' => ['required_unless:methode,simulation', 'nullable', 'string', 'max:20'],
        ]);

        $contrat = Contrat::findOrFail($data['contrat_id']);

        // Vérifier que le contrat appartient au client connecté
        abort_unless($contrat->client_id === Auth::id(), 403);

        $payment = Payment::create([
            'contrat_id'    => $contrat->id,
            'client_id'     => Auth::id(),
            'montant'       => $contrat->prime,
            'methode'       => $data['methode'],
            'numero_payeur' => $data['numero_payeur'] ?? null,
            'periode_debut' => $contrat->date_debut,
            'periode_fin'   => $contrat->date_fin,
            'statut'        => 'en_attente',
        ]);

        if ($data['methode'] === 'simulation') {
            return $this->simulerPaiement($payment, $contrat);
        }

        return redirect()->route('client.contrats.show', $contrat)
            ->with('info', 'Paiement initié. En attente de confirmation.');
    }

    // ── Simulation paiement ───────────────────────────────────────────────
    private function simulerPaiement(Payment $payment, Contrat $contrat)
    {
        $succes = rand(1, 10) <= 9; // 90% de succès

        $payment->update([
            'statut'         => $succes ? 'succes' : 'echec',
            'transaction_id' => 'SIM-' . strtoupper(Str::random(8)),
            'paye_le'        => $succes ? now() : null,
            'payload_retour' => [
                'simulation' => true,
                'resultat'   => $succes ? 'succes' : 'echec',
                'horodatage' => now()->toDateTimeString(),
            ],
        ]);

        if ($succes) {
            $contrat->update(['statut' => 'actif']);
            return redirect()->route('client.contrats.show', $contrat)
                ->with('success', '✅ Paiement simulé avec succès ! Contrat activé.');
        }

        return redirect()->route('client.contrats.show', $contrat)
            ->with('error', '❌ Échec du paiement simulé. Veuillez réessayer.');
    }
}