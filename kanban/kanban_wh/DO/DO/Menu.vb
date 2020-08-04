Imports System.Net


Public Class Menus

    Private Sub Button1_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button1.Click
        Form1.Show()
        Me.Hide()

        Form1.TextBox1.Focus()
    End Sub

    Private Sub Button3_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button3.Click
        Application.Exit()
    End Sub


    Private Sub Button2_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button2.Click
        If Connected Then
            Dim FileToCopy As String
            Dim NewCopy As String

            FileToCopy = "Program\Sync.csv"
            NewCopy = "Program\Transfer.csv"

            If System.IO.File.Exists(FileToCopy) = True And System.IO.File.Exists(NewCopy) = False Then

                System.IO.File.Copy(FileToCopy, NewCopy)
                MessageBox.Show("File Sukses tersimpan")
                System.IO.File.Delete(FileToCopy)
                saveCon()

            Else
                MsgBox("Mohon menunggu 1 menit untuk proses ini, karena masih ada proses sebelumnya yang belum selesai")
            End If

            saveCon()
        Else
            MsgBox("Mohon periksa jaringan anda.")
        End If
        

    End Sub

    Private Sub Menus_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        GetIPAddress()

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



    Private Sub GetIPAddress()

        Dim strHostName As String

        Dim strIPAddress As String



        strHostName = System.Net.Dns.GetHostName()

        'strIPAddress = System.Net.Dns.GetHostByName(strHostName).AddressList(0).ToString()


        'MessageBox.Show("Host Name: " & strHostName & "; IP Address: " & strIPAddress)

    End Sub

    Private Sub Button4_Click(ByVal sender As System.Object, ByVal e As System.EventArgs)
        Dim url As String = "http://KANBANSVR/kanban_wh/sync.php"

        Try
            Me.WebBrowser1.Navigate(New Uri(url))
            System.Diagnostics.Process.Start("IEsample", url)
        Catch ex As Exception
            MsgBox("gagal")
        End Try
    End Sub

    Sub saveCon()
        Dim url As String = "http://KANBANSVR/kanban_wh/sync.php"

        Try
            Me.WebBrowser1.Navigate(New Uri(url))
            'System.Diagnostics.Process.Start("IEsample", url)
        Catch ex As Exception
            MsgBox("gagal")
        End Try
    End Sub
End Class