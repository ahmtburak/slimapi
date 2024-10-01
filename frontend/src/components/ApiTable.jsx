import * as React from "react";
import Table from "@mui/material/Table";
import TableBody from "@mui/material/TableBody";
import TableCell from "@mui/material/TableCell";
import TableContainer from "@mui/material/TableContainer";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import Paper from "@mui/material/Paper";
import {
  Box,
  CircularProgress,
  IconButton,
  TablePagination,
} from "@mui/material";
import DeleteIcon from "@mui/icons-material/Delete";
import axios from "axios";
import { useState, useEffect } from "react";
import { useModal } from "../context/ModalContext";
import ConfirmModal from "./ConfirmModal";
import { toast } from "react-hot-toast";

export default function ApiTable() {
  const [apiData, setApiData] = useState(null);
  const [apiDataLength, setApiDataLength] = useState(0);
  const [page, setPage] = useState(0);
  const [rowsPerPage, setRowsPerPage] = useState(10);
  const { handleOpen } = useModal();
  const handleChangePage = (event, newPage) => {
    setPage(newPage);
  };

  const handleChangeRowsPerPage = (event) => {
    setRowsPerPage(parseInt(event.target.value, 10));
    setPage(0);
  };

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get("http://localhost:8888/getData");
        setApiData(response.data);
        console.log(response.data);
        setApiDataLength(response.data.length);
      } catch (error) {
        console.error("API'den veri çekerken hata oluştu:", error);
        toast.error("Veri yüklenirken bir hata oluştu.");
      }
    };

    fetchData();
  }, []);

  const filteredData = apiData
    ? apiData.slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
    : [];

  const handleDelete = async (row) => {
    try {
      await axios.delete(`http://localhost:8888/posts/${row.id}`);
      setApiData((prevData) => prevData.filter((item) => item.id !== row.id));
      toast.success("Veri başarıyla silindi.");
    } catch (error) {
      console.error("API silme isteğinde bir hata oluştu:", error);
      toast.error("API silme isteğinde bir hata oluştu.");
    }
  };
  return (
    <Box>
      <TableContainer component={Paper}>
        <Table sx={{ minWidth: 650 }} aria-label="simple table">
          <TableHead>
            <TableRow>
              <TableCell>Username</TableCell>
              <TableCell>Title</TableCell>
              <TableCell>Body</TableCell>
              <TableCell>Process</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {apiData ? (
              filteredData.map((row) => (
                <TableRow
                  key={row.id}
                  sx={{ "&:last-child td, &:last-child th": { border: 0 } }}
                >
                  <TableCell component="th" scope="row">
                    {row.username}
                  </TableCell>
                  <TableCell>{row.title}</TableCell>
                  <TableCell>{row.body}</TableCell>
                  <TableCell>
                    <IconButton
                      variant="outlined"
                      color="error"
                      onClick={() => handleOpen(row)}
                    >
                      <DeleteIcon />
                    </IconButton>
                  </TableCell>
                </TableRow>
              ))
            ) : (
              <CircularProgress />
            )}
          </TableBody>
        </Table>
      </TableContainer>
      <TablePagination
        rowsPerPageOptions={[10, 25, 50]}
        component="div"
        count={apiDataLength}
        rowsPerPage={rowsPerPage}
        page={page}
        onPageChange={handleChangePage}
        onRowsPerPageChange={handleChangeRowsPerPage}
      />
      <ConfirmModal onConfirm={handleDelete} />
    </Box>
  );
}
