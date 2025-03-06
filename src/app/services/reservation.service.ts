import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ReservationService {
  private apiUrl = 'http://localhost/Mon-salon-coiffure/backend/controllers/reservation.php';

  constructor(private http: HttpClient) {}

  addReservation(user_id: number | null, date: string, time: string): Observable<any> {
    if (!user_id) {
      console.error("Erreur : user_id est null !");
      return new Observable(observer => {
        observer.error("Données utilisateur manquantes !");
        observer.complete();
      });
    }

    const headers = new HttpHeaders({ 'Content-Type': 'application/json' });

    return this.http.post(this.apiUrl, { user_id, date, time }, { headers });
  }

  getReservations(): Observable<any> {
    return this.http.get(this.apiUrl);
  }

  //  Approuver une réservation (Ajout des headers)
  approveReservation(requestData: { action: string, reservation_id: number }): Observable<any> {
    const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
    return this.http.post(this.apiUrl, requestData, { headers });
  }

  //  Annuler une réservation (Ajout des headers)
  cancelReservation(requestData: { action: string, reservation_id: number }): Observable<any> {
    const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
    return this.http.post(this.apiUrl, requestData, { headers });
  }
}
