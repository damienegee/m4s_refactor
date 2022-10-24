import React from 'react';
import {createRoot} from 'react-dom/client';
import { Routes, Route } from 'react-router-dom';
import Home from './pages/Home/Home';
import Users from './pages/UsersPagina/Users';

const container: HTMLElement|null = document.getElementById('root');
// @ts-ignore
const root = createRoot(container);
export const App = () => {
	return (
		<div>
			<Users></Users>
		</div>
	);
}

root.render(<App />)
