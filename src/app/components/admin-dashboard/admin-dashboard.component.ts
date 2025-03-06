//http://localhost:4200/admin-dashboard//
import { Component, OnInit } from '@angular/core';
import { ReservationService } from '../../services/reservation.service';
import { NgForOf, NgIf } from '@angular/common';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  templateUrl: './admin-dashboard.component.html',
  imports: [
    NgIf,
    NgForOf
  ],
  styleUrls: ['./admin-dashboard.component.css']
})
export class AdminDashboardComponent implements OnInit {
  reservations: any[] = [];

  constructor(private reservationService: ReservationService) {}

  ngOnInit() {
    this.loadReservations();
  }

  loadReservations() {
    this.reservationService.getReservations().subscribe({
      next: (data) => {
        console.log('Réponse de l\'API:', data); // Vérifie le contenu
        this.reservations = data;
      },
      error: (err) => console.error("Erreur lors du chargement des réservations :", err)
    });
  }

  approveReservation(reservation_id: number): void {
    const requestData = {
      action: 'approve',
      reservation_id: reservation_id
    };

    // Envoi de la requête avec les bonnes données
    this.reservationService.approveReservation(requestData).subscribe({
      next: (response) => {
        console.log(response.message); // Afficher le message de succès ou erreur
        this.loadReservations(); // Recharge les réservations après l'approbation
      },
      error: (err) => {
        console.error("Erreur lors de l'approbation :", err);
      }
    });
  }

  cancelReservation(reservation_id: number): void {
    const requestData = {
      action: 'cancel',
      reservation_id: reservation_id
    };

    // Envoi de la requête avec les bonnes données
    this.reservationService.cancelReservation(requestData).subscribe({
      next: (response) => {
        console.log(response.message); // Afficher le message de succès ou erreur
        this.loadReservations(); // Recharge les réservations après l'annulation
      },
      error: (err) => {
        console.error("Erreur lors de l'annulation :", err);
      }
    });
  }
}
