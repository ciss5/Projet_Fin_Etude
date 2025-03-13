import { Component } from '@angular/core';
import { CommonModule, NgFor } from '@angular/common';
import { NavbarComponent } from '../home/navbar/navbar.component';

@Component({
  selector: 'app-prestation',
  standalone: true,
  templateUrl: './prestation.component.html',
  styleUrls: ['./prestation.component.css'],
  imports: [CommonModule, NgFor, NavbarComponent]
})
export class PrestationComponent {
  services = [
    {
      title: "Coupe",
      image: "assets/images/prestation/coupe.jpg",
      description: "Une coupe adaptée à votre style et votre morphologie.",
      details: {
        femme: {
          text: "Coupe tendance et adaptée à votre visage.",
          image: "assets/images/details/femme-coupe.jpg"
        },
        homme: {
          text: "Coupe moderne ou classique pour hommes.",
          image: "assets/images/details/homme-coupe.jpg"
        },
        enfant: {
          text: "Coupe enfant adaptée et facile à entretenir.",
          image: "assets/images/details/enfant-coupe.jpg"
        }
      }
    },
    {
      title: "texte",
      image: "assets/images/prestation/coupe.jpg",
      description: "Une coupe adaptée à votre style et votre morphologie.",
      details: {
        femme: {
          text: "Coupe tendance et adaptée à votre visage.",
          image: "assets/images/details/femme-coupe.jpg"
        },
        homme: {
          text: "Coupe moderne ou classique pour hommes.",
          image: "assets/images/details/homme-coupe.jpg"
        },
        enfant: {
          text: "Coupe enfant adaptée et facile à entretenir.",
          image: "assets/images/details/enfant-coupe.jpg"
        }
      }
    },
  ];

  tarifs = {
    dames: [
      { title: "Shampooing et Coiffage", price: "25€" },
      { title: "Shampooing, coupe brushing", price: "40€" },
      { title: "Shamp, coupe, brushing, soin / courts", price: "50€" }
    ],
    hommes: [
      { title: "Shampooing et Coupe", price: "20€" },
      { title: "Coupe simple", price: "15€" }
    ],
    enfants: [
      { title: "Coupe enfant -10 ans", price: "15€" },
      { title: "Coupe ado (10-16 ans)", price: "18€" }
    ]
  };

  categories: Array<'dames' | 'hommes' | 'enfants'> = ['dames', 'hommes', 'enfants'];
  selectedService: any = this.services[0];

  selectService(service: any) {
    this.selectedService = service;
  }

}
