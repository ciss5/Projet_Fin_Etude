import { Component, OnInit } from '@angular/core';
import { CommonModule, NgFor } from '@angular/common';
import { NavbarComponent } from '../home/navbar/navbar.component';
import { ViewportScroller } from '@angular/common'; // Importez ViewportScroller

@Component({
  selector: 'app-prestation',
  standalone: true,
  templateUrl: './prestation.component.html',
  styleUrls: ['./prestation.component.css'],
  imports: [CommonModule, NgFor, NavbarComponent]
})
export class PrestationComponent implements OnInit { // Implémentez OnInit
  services = [
    {
      title: "Coupe",
      image: "assets/images/prestation/femme/coupe.jpg",
      description: "Une coupe adaptée à votre style et votre morphologie.",
      details: {
        femme: {
          text: "Coupe tendance et adaptée à votre visage.",
          image: "assets/images/prestation/femme/coupe1.jpg",
        },
        homme: {
          text: "Coupe moderne ou classique pour hommes.",
          image: "assets/images/prestation/homme/coupe.jpg"
        },
        enfant: {
          text: "Coupe enfant adaptée et facile à entretenir.",
          image: "assets/images/prestation/enfant/coupe1home.jpg"
        }
      }
    },
    {
      title: "Coiffure",
      image: "assets/images/prestation/femme/coiffure.jpg",
      description: "Une coupe adaptée à votre style et votre morphologie.",
      details: {
        femme: {
          text: "Coiffure élégante et personnalisée.",
          image: "assets/images/prestation/femme/coiffure1.jpg",
        },
        homme: {
          text: "Style soigné pour toutes occasions.",
          image: "assets/images/prestation/homme/coupe1.jpg",
        },
        enfant: {
          text: "Coiffure enfant adaptée et tendance.",
          image: "assets/images/prestation/enfant/coiffure.jpg"
        }
      }
    },
    {
      title: "Soin",
      image: "assets/images/prestation/femme/soin.jpg",
      description: "Des soins capillaires nourrissants et réparateurs.",
      details: {
        femme: {
          text: "Soin profond pour cheveux abîmés.",
          image: "assets/images/prestation/femme/soin1.jpg",
        },
        homme: {
          text: "Traitement revitalisant pour cheveux masculins.",
          image: "assets/images/prestation/homme/soin.jpg"
        },
      }
    },
    {
      title: "Perruques",
      image: "assets/images/prestation/femme/perruque.jpg",
      description: "Large choix de perruques naturelles et synthétiques.",
      details: {
        femme: {
          text: "Styles variés et confortables.",
          image: "assets/images/prestation/femme/perruque1.jpg",
        },
      }
    },
    {
      title: "Lissage",
      image: "assets/images/prestation/femme/lissage.jpg",
      description: "Un lissage professionnel pour des cheveux soyeux.",
      details: {
        femme: {
          text: "Lissage brésilien pour une chevelure lisse et brillante.",
          image: "assets/images/prestation/femme/lissage.jpg",
        }
      }
    }
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

  // Injectez ViewportScroller dans le constructeur
  constructor(private viewportScroller: ViewportScroller) {}

  // Implémentez ngOnInit pour déclencher le défilement vers le haut
  ngOnInit(): void {
    this.viewportScroller.scrollToPosition([0, 0]); // Défile vers le haut de la page
  }

  selectService(service: any) {
    this.selectedService = service;
    //this.viewportScroller.scrollToPosition([0, 0]); // Défile vers le haut après la sélection
  }
}
