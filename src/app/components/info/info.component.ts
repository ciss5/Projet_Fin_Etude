import { Component } from '@angular/core';
import {NgForOf} from '@angular/common';

@Component({
  selector: 'app-info',
  standalone: true,
  templateUrl: './info.component.html',
  imports: [
    NgForOf
  ],
  styleUrls: ['./info.component.css']
})
export class InfoComponent {
  salonAdresse = {
    rue: "123 Avenue de la Beauté",
    ville: "Paris",
    codePostal: "75001",
    pays: "France"
  };

  horaires = [
    { jour: "Lundi", heures: "Fermé" },
    { jour: "Mardi", heures: "09:00 - 19:00" },
    { jour: "Mercredi", heures: "09:00 - 19:00" },
    { jour: "Jeudi", heures: "09:00 - 19:00" },
    { jour: "Vendredi", heures: "09:00 - 19:00" },
    { jour: "Samedi", heures: "09:00 - 17:00" },
    { jour: "Dimanche", heures: "Fermé" }
  ];
}
