import React from 'react';
import {createRoot} from 'react-dom/client';
import Home from './pages/Home/Home';

const container: HTMLElement|null = document.getElementById('root');
// @ts-ignore
const root = createRoot(container);
export const App = () => {
	return (
		<div>
			<Home/>
		</div>
	);
}

root.render(<App />)
