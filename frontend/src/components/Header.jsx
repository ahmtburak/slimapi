import React from "react";
import AppBar from "@mui/material/AppBar";
import Box from "@mui/material/Box";
import Toolbar from "@mui/material/Toolbar";
import Typography from "@mui/material/Typography";
import Container from "@mui/material/Container";
import CodeIcon from "@mui/icons-material/Code";
const Header = () => {
  return (
    <AppBar position="static">
      <Container maxWidth="xxl">
        <Toolbar disableGutters>
          <CodeIcon sx={{ display: { xs: "none", md: "flex" }, mr: 1 }} />
          <Typography
            variant="h6"
            noWrap
            component="a"
            href="/"
            sx={{
              mr: 2,
              display: { xs: "none", md: "flex" },
              fontFamily: "BlinkMacSystemFont",
              fontWeight: 700,
              letterSpacing: ".3rem",
              color: "inherit",
              textDecoration: "none",
            }}
          >
            Ahmet Burak Çetin
            
          </Typography>

          <Box
            sx={{
              flexGrow: 1,
              flex: 1,
              justifyContent: "end",
              display: { md: "flex" },
            }}
          >
            Aesthetic Coding
          </Box>
        </Toolbar>
      </Container>
    </AppBar>
  );
};

export default Header;
