import { Component } from '@angular/core';
import {NavbarComponent} from './navbar/navbar.component';
import {RouterOutlet} from '@angular/router';
import {MainComponent} from './main/main.component';
import {InfoComponent} from '../info/info.component';
import {MarquesComponent} from '../marques/marques.component';
import {FooterComponent} from '../footer/footer.component';
@Component({
  selector: 'app-home',
  standalone: true,
  imports: [
    NavbarComponent,
    RouterOutlet,
    MainComponent,
    InfoComponent,
    MarquesComponent,
    FooterComponent
  ],
  templateUrl: './home.component.html',
  styleUrl: './home.component.css'
})
export class HomeComponent {

}
