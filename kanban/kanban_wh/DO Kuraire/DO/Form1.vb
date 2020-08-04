Imports System.Net

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
                Me.WebBrowser1.Refresh()
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

        Dim s As String = TextBox1.Text


        TextBox2.Text = Trim(s.Substring(0, 10))
        TextBox17.Text = Trim(s.Substring(30, 20))
        TextBox16.Text = Trim(s.Substring(50, 5))
        TextBox3.Text = CDbl(Trim(s.Substring(60, 10)))
        TextBox4.Text = Trim(s.Substring(85, 5))

    End Sub

    Private Sub Button1_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button1.Click
        Me.Close()

    End Sub

    Private Sub Button2_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button2.Click

        If Connected Then
            Dim url As String = "http://kanbansvr/wms/kanban/checkid.php?var1=" & TextBox2.Text & ""

            Try
                ''Me.WebBrowser1.Refresh()

                Me.WebBrowser1.Navigate(New Uri(url))
                'System.Diagnostics.Process.Start("IEsample", url)
            Catch ex As Exception
                ''MsgBox("gagal")
                MsgBox(ex.Message)
            End Try


            If TextBox2.Text <> "" Then
                CreateCSVfile("Program\kuraire.csv", TextBox2.Text, TextBox2.Text, TextBox2.Text)
                TextBox2.Text = Nothing
                TextBox3.Text = Nothing
            End If
        Else
            MsgBox("Jaringan tidak Connect, mohon cek kembali jaringan.")
        End If

        TextBox18.Text = Nothing
        TextBox1.Text = Nothing
        TextBox1.Focus()
        TextBox17.Text = Nothing
        TextBox16.Text = Nothing
        TextBox2.Text = Nothing
        TextBox3.Text = Nothing
        TextBox4.Text = Nothing



    End Sub

    Public Shared ReadOnly Property Connected() As Boolean
        Get
            Dim ret As Boolean
            Try
                ' Returns the Device Name
                Dim HostName As String = Dns.GetHostName()
                Dim thisHost As IPHostEntry = Dns.GetHostByName(HostName)
                Dim thisIpAddr As String = thisHost.AddressList(0).ToString

                ret = thisIpAddr <> _
                  Net.IPAddress.Parse("172.23.225.85").ToString()

            Catch ex As Exception
                Return False
            End Try

            Return ret
        End Get
    End Property

    Private Sub Button3_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button3.Click
        TextBox18.Text = Nothing
        TextBox17.Text = Nothing
        TextBox16.Text = Nothing
        TextBox1.Text = Nothing
        TextBox1.Focus()
        TextBox2.Text = Nothing
        TextBox3.Text = Nothing
        TextBox4.Text = Nothing




    End Sub
End Class
