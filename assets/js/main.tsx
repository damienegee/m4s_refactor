import React from 'react';
import {createRoot} from 'react-dom/client';

const container: HTMLElement|null = document.getElementById('root');
// @ts-ignore
const root = createRoot(container);
export const App = () => {
	return (
		<div>

		</div>
	);
}

root.render(<App />)
