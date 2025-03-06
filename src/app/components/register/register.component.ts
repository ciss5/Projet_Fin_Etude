import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import {FormsModule} from '@angular/forms';
import {CommandModule} from '@angular/cli/src/command-builder/command-module';
import {CommonModule} from '@angular/common';

@Component({
  standalone: true,
  selector: 'app-register',
  templateUrl: './register.component.html',
  imports: [
    FormsModule,
    CommonModule,
  ],
  styleUrls: ['./register.component.css']
})
export class RegisterComponent {
  name = '';
  email = '';
  password = '';
  message = '';

  constructor(private authService: AuthService) {}


  onRegister() {
    this.authService.register(this.name, this.email, this.password).subscribe({
      next: (response) => this.message = 'Inscription réussie !',
      error: (error) => this.message = 'Erreur lors de l\'inscription.',
      complete: () => console.log('Inscription terminée') // Optionnel
    });
  }

}
