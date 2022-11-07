import React from 'react';
import {createRoot} from 'react-dom/client';
import Home from './pages/Home/Home';
import Inventaris from './pages/Inventaris/Inventaris';

const container: HTMLElement|null = document.getElementById('root');
// @ts-ignore
const root = createRoot(container);
export const App = () => {
	return (
		<div>
			<Inventaris/>
		</div>
	);
}

root.render(<App />)
