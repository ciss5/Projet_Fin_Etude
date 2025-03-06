import { Component, OnInit } from '@angular/core';
import { ReservationService } from '../../services/reservation.service';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-reservation',
  standalone: true,
  templateUrl: './reservation.component.html',
  imports: [
    FormsModule,
    CommonModule,
  ],
  styleUrls: ['./reservation.component.css']
})
export class ReservationComponent implements OnInit {
  date = '';
  time = '';
  reservations: any[] = [];
  message = '';

  constructor(private reservationService: ReservationService) {}

  ngOnInit(): void {
    this.loadReservations();

  }

  onReserve(): void {
    const user_id = this.getUserId(); // Récupère l'ID de l'utilisateur connecté

    this.reservationService.addReservation(user_id, this.date, this.time).subscribe({
      next: (response) => {
        this.message = response.message;
        this.loadReservations();
      },
      error: () => {
        this.message = 'Erreur lors de la réservation.';
      }
    });
  }

  loadReservations(): void {
    this.reservationService.getReservations().subscribe({
      next: (data) => {
        this.reservations = data;
      },
      error: (err) => {
        console.error('Erreur lors du chargement des réservations:', err);
      }
    });
  }

  // ⚡ Fonction pour récupérer l'ID de l'utilisateur connecté (à adapter selon ton auth system)
  getUserId(): number | null {
    const userData = localStorage.getItem('user'); // Récupérer les données de l'utilisateur
    if (!userData) {
      console.error('Utilisateur non connecté ou données manquantes dans localStorage');
      return null;
    }

    try {
      const user = JSON.parse(userData); // Parser les données si elles existent
      return user.id || null; // Vérifier si l'ID utilisateur est bien défini
    } catch (error) {
      console.error('Erreur lors du parsing des données utilisateur', error);
      return null;
    }
  }

}
