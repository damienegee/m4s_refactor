import { Box } from "@mui/material";
import { GridToolbarContainer, GridToolbarDensitySelector, GridToolbarExport, GridToolbarFilterButton, GridToolbarQuickFilter } from "@mui/x-data-grid";

const CustomToolbar = () => {
    return (
      <GridToolbarContainer style={{justifyContent:"space-between"}}>
        <Box>
          <GridToolbarDensitySelector />
          <GridToolbarExport printOptions={{ disableToolbarButton: true }} />
          <GridToolbarFilterButton />
        </Box>
        <GridToolbarQuickFilter />
      </GridToolbarContainer>
    );
  }

  export default CustomToolbar;