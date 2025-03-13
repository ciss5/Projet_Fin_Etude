import {provideRouter, Routes, withRouterConfig} from '@angular/router';
import {HomeComponent} from './components/home/home.component';
//import { importProvidersFrom } from '@angular/core';
//import { LoginComponent } from './components/login/login.component';
//import { RegisterComponent } from './components/register/register.component';
export const routes: Routes = [
  { path: '', redirectTo: 'home', pathMatch: 'full' },
  { path: 'home', loadComponent: () => import('./components/home/home.component').then(m => m.HomeComponent) },
  { path: 'login', loadComponent: () => import('./components/login/login.component').then(m => m.LoginComponent) },
  { path: 'register', loadComponent: () => import('./components/register/register.component').then(m => m.RegisterComponent) },
  { path: 'reservation', loadComponent: () => import('./components/reservation/reservation.component').then(m => m.ReservationComponent) },
  { path: 'admin-dashboard', loadComponent: () => import('./components/admin-dashboard/admin-dashboard.component').then(m => m.AdminDashboardComponent) },
  { path: 'prestation', loadComponent: () => import('./components/prestation/prestation.component').then(m => m.PrestationComponent) }
];
// Active le scroll automatique en haut après navigation
//export const appRouting = provideRouter(routes, withRouterConfig({ scrollPositionRestoration: 'enabled' }));
