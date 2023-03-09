import grid from '../../../resources/grid/grid.json'


const createNode = ( template ) => {
	const wrapper = document.createElement( 'div' );
	wrapper.innerHTML = template;

	return wrapper.firstElementChild;
};

const Keycode = {
	ENTER: 13,
	ESC: 27,
	ARROW_TOP: 38,
	ARROW_BOTTOM: 40,
	ARROW_LEFT: 37,
	ARROW_RIGHT: 39,
};

const GRID_HELP_BLOCK_ATTRIBUTE = 'data-grid-help-block';

const GRID_HELP_BLOCK_CLASS = 'grid-help-block';
const GRID_HELP_BLOCK_TEMPLATE = `<div class="${GRID_HELP_BLOCK_CLASS}"></div>`;

const createGridHelpBlock = ( gridObject ) => {
	if ( !gridObject )
	{
		return;
	}

	const breakpoints = Object.values( gridObject ).sort( ( a, b ) => {
		if ( a.full_width && b.full_width )
		{
			if ( a.full_width > b.full_width )
			{
				return 1;
			}

			return -1;
		}

		return 0;
	} );

	let gridHelpBlock = document.querySelector( `body > div.${GRID_HELP_BLOCK_CLASS}` );

	if ( !gridHelpBlock )
	{
		gridHelpBlock = createNode( GRID_HELP_BLOCK_TEMPLATE );
	}

	let styles = '';
	let maxColumnCount = 0;

	for ( const breakpoint of breakpoints ) {
		const {'full_width': fullWidth, 'column_count': columnCount} = breakpoint;

		if ( !fullWidth || !columnCount )
		{
			console.error( 'Неверная структура grid.json' );

			return;
		}

		if ( columnCount > maxColumnCount )
		{
			maxColumnCount = columnCount;
		}

		styles += ( `
			@media screen and ( min-width: ${fullWidth}px )
			{
				html > body > div.${GRID_HELP_BLOCK_CLASS} > div.column:nth-of-type( n )
				{
					display: block;
				}

				html > body > div.${GRID_HELP_BLOCK_CLASS} > div.column:nth-of-type( n + ${columnCount + 1})
				{
					display: none;
				}
			}
		` );
	}

	const styleNode = createNode( `<style>${styles}</style>` );

	gridHelpBlock.appendChild( styleNode );
	for ( let i = 0; i < maxColumnCount; i++ )
	{
		gridHelpBlock.appendChild( createNode( '<div class="column"></div>' ) );
	}

	document.body.appendChild( gridHelpBlock );
};

function runOnKeys(func, ...codes) {
	let pressed = new Set();

	document.addEventListener('keydown', function(event) {
		pressed.add(event.code);

		for (let code of codes) {
			if (!pressed.has(code)) {
				return;
			}
		}

		pressed.clear();

		const helpBlock = document.querySelector( 'div.grid-help-block' );

		if ( helpBlock )
		{
			helpBlock.remove()
		}
		else
		{
			func();
		}
	});

	document.addEventListener('keyup', function(event) {
		pressed.delete(event.code);
	});

}

const initGridHelpBlock = () => {
	if ( grid )
	{
		window.createGridHelpBlock = () => {
			createGridHelpBlock( grid );
		};

		if ( document.body.getAttribute( GRID_HELP_BLOCK_ATTRIBUTE ))
		{
			createGridHelpBlock( grid );
		}

		runOnKeys(
			() => createGridHelpBlock( grid ),
			"Digit4",
			"ShiftLeft",
			"ControlLeft"
		);
	}
};

initGridHelpBlock();
