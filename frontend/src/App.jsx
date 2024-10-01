import * as React from "react";
import Header from "./components/Header";
import ApiTable from "./components/ApiTable";
import { Box, Container } from "@mui/material";
import { ModalProvider } from "./context/ModalContext";
import { Toaster } from "react-hot-toast";

function App() {
  return (
    <>
      <ModalProvider>
        <Header />
        <Box sx={{ p: 6 }}>
          <ApiTable />
        </Box>
        <Toaster />
      </ModalProvider>
    </>
  );
}
export default App;
