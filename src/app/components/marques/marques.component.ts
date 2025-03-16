import { Component } from '@angular/core';
import {NgForOf} from '@angular/common';

@Component({
  selector: 'app-marques',
  standalone: true,
  templateUrl: './marques.component.html',
  imports: [
    NgForOf
  ],
  styleUrls: ['./marques.component.css']
})
export class MarquesComponent {
  marques = [
    { name: "Redken", image: "assets/images/marque/redken1.png" },
    { name: "Revlon", image: "assets/images/marque/revlon.png" },
    { name: "Schwarzkopf", image: "assets/images/marque/sch.png" },
    { name: "Wella", image: "assets/images/marque/wella.png" }
  ];
}
