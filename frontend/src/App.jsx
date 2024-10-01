import * as React from "react";
import Header from "./components/Header";
import ApiTable from "./components/ApiTable";
import { Box, Container } from "@mui/material";

function App() {
  return (
    <>
      <Header />
      <Box sx={{ p: 6 }}>
          <ApiTable />
      </Box>
    </>
  );
}
export default App;
