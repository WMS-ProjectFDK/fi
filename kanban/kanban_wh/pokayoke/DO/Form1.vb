Public Class Form1

    Private Sub Form1_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load

    End Sub

    Private Sub TextBox1_TextChanged(ByVal sender As System.Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles TextBox1.KeyDown

        Try

            If e.KeyCode = Keys.Enter Then
                open()
                TextBox1.Text = Trim(Replace(TextBox1.Text, vbCrLf, ""))
                TextBox1.Text = Nothing

                TextBox1.Focus()

            End If
        Catch ex As Exception

            TextBox1.Text = Nothing
        End Try
        'TextBox1.Text = ID.Replace(vbCrLf, "")
    End Sub

    Private Sub CreateCSVfile(ByVal _strCustomerCSVPath As String, ByVal _ID As String, ByVal _MaterialNo As String, ByVal _Qty As String)
        Try
            Dim stLine As String = ""
            Dim objWriter As IO.StreamWriter = IO.File.AppendText(_strCustomerCSVPath)
            If IO.File.Exists(_strCustomerCSVPath) Then

                objWriter.Write(_ID & ",")
                objWriter.Write(_MaterialNo & ",")
                objWriter.Write(_Qty & ",")
                objWriter.Write(Now & ",")
                objWriter.Write(TextBox16.Text & ",")
                objWriter.Write(TextBox17.Text & ",")

                'If value contains comma in the value then you have to perform this opertions

                objWriter.Write(Environment.NewLine)
            End If
            objWriter.Close()

        Catch ex As Exception
        End Try
    End Sub


    Sub open()
        Dim i As Integer = 1

        Dim s As String = TextBox1.Text
        Dim parts As String() = s.Split(New Char() {","c})
        Dim part As String
        For Each part In parts
            'item,qty,pallet,ID
            If i = 1 Then
                TextBox18.Text = part
            End If

            If i = 2 Then
                TextBox17.Text = part
            End If
            If i = 3 Then
                TextBox16.Text = part
            End If
            If i = 4 Then
                TextBox2.Text = part
            End If
            If i = 5 Then
                TextBox3.Text = part
            End If

            If i = 6 Then
                TextBox11.Text = part
            End If
            If i = 7 Then
                TextBox10.Text = part
            End If
            If i = 8 Then
                TextBox9.Text = part
            End If
            If i = 9 Then
                TextBox8.Text = part
            End If

            If i = 10 Then
                TextBox7.Text = part
            End If
            If i = 11 Then
                TextBox6.Text = part
            End If
            If i = 12 Then
                TextBox5.Text = part
            End If
            If i = 13 Then
                TextBox4.Text = part
            End If

            If i = 14 Then
                TextBox13.Text = part
            End If
            If i = 15 Then
                TextBox12.Text = part
            End If
            If i = 16 Then
                TextBox15.Text = part
            End If
            If i = 17 Then
                TextBox14.Text = part
            End If
            If i = 18 Then
                TextBox20.Text = part
            End If
            If i = 19 Then
                TextBox19.Text = part
            End If
            If i = 20 Then
                TextBox22.Text = part
            End If
            If i = 21 Then
                TextBox21.Text = part
            End If
            If i = 22 Then
                TextBox24.Text = part
            End If
            If i = 23 Then
                TextBox23.Text = part
            End If


            i += 1


        Next
    End Sub

    Private Sub Button1_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button1.Click
        Me.Hide()
        Menus.Show()

    End Sub

    Private Sub Button2_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button2.Click
        If TextBox2.Text <> "" Then
            CreateCSVfile("Program\Transfer.csv", TextBox18.Text, TextBox2.Text, TextBox3.Text)
            TextBox2.Text = Nothing
            TextBox3.Text = Nothing

        End If

        If TextBox11.Text <> "" Then
            CreateCSVfile("Program\Transfer.csv", TextBox18.Text, TextBox11.Text, TextBox10.Text)
            TextBox11.Text = Nothing
            TextBox10.Text = Nothing
        End If

        If TextBox9.Text <> "" Then
            CreateCSVfile("Program\Transfer.csv", TextBox18.Text, TextBox9.Text, TextBox8.Text)
            TextBox9.Text = Nothing
            TextBox8.Text = Nothing
        End If

        If TextBox7.Text <> "" Then
            CreateCSVfile("Program\Transfer.csv", TextBox18.Text, TextBox7.Text, TextBox6.Text)
            TextBox7.Text = Nothing
            TextBox6.Text = Nothing
        End If

        If TextBox5.Text <> "" Then
            CreateCSVfile("Program\Transfer.csv", TextBox18.Text, TextBox5.Text, TextBox4.Text)
            TextBox5.Text = Nothing
            TextBox4.Text = Nothing
        End If

        If TextBox13.Text <> "" Then
            CreateCSVfile("Program\Transfer.csv", TextBox18.Text, TextBox13.Text, TextBox12.Text)
            TextBox13.Text = Nothing
            TextBox12.Text = Nothing
        End If

        If TextBox15.Text <> "" Then
            CreateCSVfile("Program\Transfer.csv", TextBox18.Text, TextBox15.Text, TextBox14.Text)
            TextBox15.Text = Nothing
            TextBox14.Text = Nothing
        End If


        If TextBox20.Text <> "" Then
            CreateCSVfile("Program\Transfer.csv", TextBox18.Text, TextBox20.Text, TextBox19.Text)
            TextBox20.Text = Nothing
            TextBox19.Text = Nothing
        End If


        If TextBox22.Text <> "" Then
            CreateCSVfile("Program\Transfer.csv", TextBox18.Text, TextBox22.Text, TextBox21.Text)
            TextBox22.Text = Nothing
            TextBox21.Text = Nothing
        End If


        If TextBox24.Text <> "" Then
            CreateCSVfile("Program\Transfer.csv", TextBox18.Text, TextBox24.Text, TextBox23.Text)
            TextBox24.Text = Nothing
            TextBox23.Text = Nothing
        End If
        TextBox18.Text = Nothing
        TextBox1.Text = Nothing
        TextBox1.Focus()
        TextBox17.Text = Nothing
        TextBox16.Text = Nothing

    End Sub

    Private Sub Button3_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button3.Click
        TextBox19.Text = Nothing
        TextBox20.Text = Nothing
        TextBox21.Text = Nothing
        TextBox22.Text = Nothing
        TextBox23.Text = Nothing
        TextBox24.Text = Nothing

        TextBox18.Text = Nothing
        TextBox17.Text = Nothing
        TextBox16.Text = Nothing
        TextBox1.Text = Nothing
        TextBox1.Focus()
        TextBox2.Text = Nothing
            TextBox3.Text = Nothing



             TextBox11.Text = Nothing
        TextBox10.Text = Nothing

        TextBox9.Text = Nothing
             TextBox8.Text = Nothing


            TextBox7.Text = Nothing
        TextBox6.Text = Nothing


             TextBox5.Text = Nothing
        TextBox4.Text = Nothing


            TextBox13.Text = Nothing
        TextBox12.Text = Nothing


            TextBox15.Text = Nothing
        TextBox14.Text = Nothing
    End Sub
End Class
