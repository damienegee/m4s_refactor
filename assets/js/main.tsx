import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Home from './pages/Home/Home';
import Login from './pages/Login/Login';
import Users from './pages/UsersPagina/Users';
import Inventaris from './pages/Inventaris/Inventaris';
import Incident from './pages/Incident/Incident';
import { ViewportProvider } from './hooks/viewport';

const container: HTMLElement | null = document.getElementById('root');
// @ts-ignore
const root = createRoot(container);
export const App = () => {
	return (
		<>
			<ViewportProvider>
				<BrowserRouter>
					<Routes>
						<Route path='/' element={<Login />} />
						<Route path='/home' element={<Home />} />
						<Route path='/users' element={<Users />} />
						<Route path='/inventaris' element={<Inventaris />} />
						<Route path='/incidents' element={<Incident />} />
					</Routes>
				</BrowserRouter>
			</ViewportProvider>
		</>
	);
}

root.render(<App />)
