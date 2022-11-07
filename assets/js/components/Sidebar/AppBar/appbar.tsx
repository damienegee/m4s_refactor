import { GridToolbarContainer, GridToolbarDensitySelector, GridToolbarExport } from "@mui/x-data-grid";

const CustomToolbar = () => {
    return (
      <GridToolbarContainer>
        <GridToolbarDensitySelector />
        <GridToolbarExport printOptions={{ disableToolbarButton: true }} />
      </GridToolbarContainer>
    );
  }

  export default CustomToolbar;