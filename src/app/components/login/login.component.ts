import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  standalone: true,
  selector: 'app-login',
  templateUrl: './login.component.html',
  imports: [
    CommonModule,
    FormsModule
  ],
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  email = '';
  password = '';
  message = '';

  constructor(private authService: AuthService, private router: Router) {}

  onLogin() {
    this.authService.login(this.email, this.password).subscribe({
      next: (response) => {
        if (response.status === "success" && response.user) {
          this.authService.storeUserData(response.user, response.token); // Stocke les infos utilisateur
          this.message = 'Connexion réussie !';
          this.router.navigate(['/dashboard']); // Rediriger vers la page d'accueil
        } else {
          this.message = 'Email ou mot de passe incorrect.';
        }
      },
      error: (error) => {
        console.error("Erreur de connexion :", error);
        this.message = 'Erreur de connexion.';
      }
    });
  }


}
