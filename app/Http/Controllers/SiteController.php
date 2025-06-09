<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Patient;

use Carbon\Carbon;

class SiteController extends Controller {

    public function getIndex(Request $request) {
		return view('index');
	}

	// ------------------ Cliente ------------------
	public function getClient(Request $request) {
		return view('client');
	}

	public function getEditPatient($patient_id = null) {
		$user = auth()->User();
		if (!$patient_id) {
			$patient = Patient::where([ 'user_id' => $user->id, 'name' => null ])->first();

			if (!$patient) {
				$patient = Patient::create([ 'user_id' => $user->id ]);
			}

			return redirect()->route('client.edit-patient', $patient->id);
		}
		else {
			$patient = Patient::where([ 'id' => $patient_id ])->first();
		}

		return view('edit-patient', [ 'patient' => $patient ]);
	}

	public function postEditPatient($patient_id, Request $request) {
		$patient = Patient::find($patient_id);

        $request->validate([
            'birthdate' => 'required|date_format:d/m/Y',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('patients', 'public');
            $patient->picture = $picturePath;
        }

		$data = array_merge($request->except('birthdate'), [ 'birthdate' => Carbon::createFromFormat('d/m/Y', $request->birthdate) ]);
		$patient->update( $data );
        $patient->save();

		return redirect()->route('client')->with('toast', 'Paciente salvo com sucesso.');
	}

	public function getRemovePatient($patient_id) {
		$patient = Patient::find($patient_id);
		$patient->delete();

		return redirect()->route('client')->with('toast', 'Paciente removido com sucesso.');
	}

	public function getAppointment($appointment_id) {
		// - TODO: Retornar consulta
		$appointment = null;
		return view('appointment', [ 'appointment' => $appointment ]);
	}

	public function getCreateAppointment() {
		return view('create-appointment');
	}

	public function postCreateAppointment(Request $request) {
		// - TODO: Agendar a consulta
		return redirect()->route('client')->with('toast', 'Consulta marcada com sucesso.');
	}

	// ------------------ Veterinário ------------------
	public function getVet(Request $request) {
		// - TODO: Retornar todos os agendamentos
		$appointments = [];
		return view('vet', [ 'appointments' => $appointments ]);
	}

	public function getEditAppointment($appointment_id) {
		// - TODO: Retornar consulta
		$appointment = null;
		return view('edit-appointment', [ 'appointment' => $appointment ]);
	}

}
