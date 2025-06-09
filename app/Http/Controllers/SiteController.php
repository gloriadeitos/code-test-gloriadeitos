<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Patient;

use App\Models\Appointment;

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
            'breed' => 'required|string|max:255',
        ]);

        $data = $request->except(['birthdate', 'picture']);
        $data['birthdate'] = Carbon::createFromFormat('d/m/Y', $request->birthdate);

        if ($request->hasFile('picture')) {
            // Salva a imagem no diretório correto
            $picturePath = $request->file('picture')->store('patients', 'public');
            $data['picture'] = $picturePath;
        }

        $patient->update($data);

        return redirect()->route('client')->with('toast', 'Paciente salvo com sucesso.');
    }

	public function getRemovePatient($patient_id) {
		$patient = Patient::find($patient_id);
		$patient->delete();

		return redirect()->route('client')->with('toast', 'Paciente removido com sucesso.');
	}

    public function getAppointment($appointment_id) {
        $appointment = Appointment::with('patient')->findOrFail($appointment_id);
        return view('appointment', compact('appointment'));
    }

	public function getCreateAppointment() {
		return view('create-appointment');
	}

    public function postCreateAppointment(Request $request) {
        $request->validate([
            'patient' => 'required|exists:patients,id',
            'date' => 'required|date_format:d/m/Y',
            'time' => 'required|date_format:H:i',
        ]);

        $appointmentDateTime = Carbon::createFromFormat('d/m/Y H:i', $request->date . ' ' . $request->time);

        $existingAppointment = Appointment::where('date_time', $appointmentDateTime)->first();
        if ($existingAppointment) {
            return redirect()->back()->withErrors(['time' => 'Este horário já está ocupado.']);
        }

        Appointment::create([
            'patient_id' => $request->patient,
            'date_time' => $appointmentDateTime,
            'status' => 'AGENDADA',
        ]);

        return redirect()->route('client')->with('toast', 'Consulta marcada com sucesso.');
    }

	// ------------------ Veterinário ------------------
    public function getVet(Request $request) {
        $appointments = Appointment::with(['patient.owner'])->get();
        return view('vet', ['appointments' => $appointments]);
    }

    public function editAppointment($id){
        $appointment = Appointment::with(['patient.owner'])->findOrFail($id);
        return view('edit-appointment', ['appointment' => $appointment]);
    }

}
